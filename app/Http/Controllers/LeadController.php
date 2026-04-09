<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\ActivityLogger;
use App\Services\UserNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $key = 'lead:submit:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 8)) {
            return back()->withErrors([
                'form' => 'Terlalu banyak pengiriman form. Coba lagi sebentar lagi.',
            ])->withInput();
        }

        // Honeypot: real users never fill this hidden field.
        if (filled($request->input('website'))) {
            RateLimiter::hit($key, 300);

            return back()->with('lead_success', 'Terima kasih, tim kami akan menghubungi Anda segera.');
        }

        // Timing heuristic: too fast usually indicates bots.
        $renderedAt = (int) $request->input('form_rendered_at', 0);
        $elapsed = now()->timestamp - $renderedAt;
        if ($renderedAt <= 0 || $elapsed < 3 || $elapsed > 7200) {
            RateLimiter::hit($key, 120);

            return back()->withErrors([
                'form' => 'Terjadi kesalahan validasi form. Silakan coba kirim ulang.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:190'],
            'company' => ['nullable', 'string', 'max:190'],
            'project_brief' => ['required', 'string', 'max:2000'],
            'website' => ['nullable', 'string', 'max:0'],
            'form_rendered_at' => ['required', 'integer'],
        ]);

        $normalizedName = Str::of($validated['name'])->squish()->limit(120, '')->toString();
        $normalizedEmail = Str::lower(Str::of($validated['email'])->squish()->toString());
        $normalizedCompany = isset($validated['company']) ? Str::of($validated['company'])->squish()->limit(190, '')->toString() : null;
        $normalizedBrief = Str::of($validated['project_brief'])->replaceMatches('/\s+/u', ' ')->trim()->limit(2000, '')->toString();

        $spamTokens = ['http://', 'https://', 'bit.ly', 't.me/', 'wa.me/', 'casino', 'crypto'];
        $briefLower = Str::lower($normalizedBrief);
        foreach ($spamTokens as $token) {
            if (str_contains($briefLower, $token)) {
                RateLimiter::hit($key, 300);

                return back()->withErrors([
                    'form' => 'Konten pesan terdeteksi tidak valid. Mohon isi brief secara deskriptif.',
                ])->withInput();
            }
        }

        // Lightweight fingerprint cooldown to reduce repeated abuse patterns.
        $fingerprint = hash('sha256', implode('|', [
            $request->ip(),
            (string) $request->userAgent(),
            $normalizedEmail,
        ]));
        $fpKey = 'lead:fp:'.$fingerprint;
        $fpCount = Cache::increment($fpKey);
        if ($fpCount === 1) {
            Cache::put($fpKey, 1, now()->addMinutes(30));
        }
        if ($fpCount > 3) {
            RateLimiter::hit($key, 300);

            return back()->withErrors([
                'form' => 'Terlalu banyak percobaan pengiriman dari perangkat ini. Coba lagi nanti.',
            ])->withInput();
        }

        $lead = Lead::create([
            'name' => $normalizedName,
            'email' => $normalizedEmail,
            'company' => $normalizedCompany,
            'project_brief' => $normalizedBrief,
            'source' => 'landing_page',
            'source_ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'status' => Lead::STATUS_NEW,
            'last_activity_at' => now(),
        ]);

        ActivityLogger::log(
            'lead_submitted',
            null,
            Lead::class,
            $lead->id,
            ['email' => $lead->email, 'source' => $lead->source]
        );

        app(UserNotificationService::class)->notifyAdminsOfNewLead($lead);

        RateLimiter::hit($key, 60);

        return back()->with('lead_success', 'Terima kasih, tim kami akan menghubungi Anda segera.');
    }

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $leads = Lead::query()
            ->with('assignee:id,name')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%");
                });
            })
            ->when($status !== '' && in_array($status, Lead::statuses(), true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('last_activity_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $assignees = \App\Models\User::query()
            ->where('status_akun', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return view('admin.leads.index', compact('leads', 'q', 'status', 'assignees'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', Lead::statuses()),
            'notes' => 'sometimes|nullable|string|max:10000',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        $oldStatus = $lead->status;

        $lead->status = $validated['status'];
        if ($request->has('notes')) {
            $lead->notes = $validated['notes'];
        }
        if ($request->has('assigned_to')) {
            $lead->assigned_to = $validated['assigned_to'];
        }

        if ($lead->status === Lead::STATUS_CONTACTED && $oldStatus !== Lead::STATUS_CONTACTED && $lead->contacted_at === null) {
            $lead->contacted_at = now();
        }

        $lead->last_activity_at = now();
        $lead->save();

        if ($oldStatus !== $lead->status) {
            ActivityLogger::log(
                'lead_status_changed',
                $request->user(),
                Lead::class,
                $lead->id,
                ['from' => $oldStatus, 'to' => $lead->status]
            );
        }

        return back()->with('success', 'Lead diperbarui.');
    }
}

