<script>
    (function () {
        const THEME_KEY = 'theme';

        function getStoredTheme() {
            try {
                return localStorage.getItem(THEME_KEY);
            } catch (_) {
                return null;
            }
        }

        function resolveTheme() {
            const stored = getStoredTheme();
            if (stored === 'dark' || stored === 'light') return stored;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            const root = document.documentElement;
            const isDark = theme === 'dark';
            root.classList.toggle('dark', isDark);
            root.setAttribute('data-theme', isDark ? 'dark' : 'light');
            return isDark;
        }

        function setTheme(theme) {
            const next = theme === 'dark' ? 'dark' : 'light';
            try {
                localStorage.setItem(THEME_KEY, next);
            } catch (_) {}
            applyTheme(next);
            window.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme: next } }));
            return next;
        }

        function toggleTheme() {
            const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            return setTheme(current === 'dark' ? 'light' : 'dark');
        }

        window.getTheme = resolveTheme;
        window.applyTheme = applyTheme;
        window.setTheme = setTheme;
        window.toggleTheme = toggleTheme;

        // Apply immediately before render to prevent flicker.
        applyTheme(resolveTheme());

        document.addEventListener('livewire:init', () => applyTheme(resolveTheme()));
        document.addEventListener('livewire:load', () => applyTheme(resolveTheme()));
        document.addEventListener('livewire:navigated', () => applyTheme(resolveTheme()));
        window.addEventListener('storage', function (event) {
            if (event.key === THEME_KEY) applyTheme(resolveTheme());
        });
    })();
</script>
