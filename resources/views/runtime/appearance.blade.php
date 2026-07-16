{{-- @livewindAppearance: script anti-flash de dark mode. Vai no <head>. Removivel
     se o app ja gerencia dark mode por conta propria. Aplica a classe .dark no
     <html> conforme localStorage ('livewind.appearance': light|dark|system) ou a
     preferencia do sistema, e expoe window.Livewind.appearance. --}}
<script>
    (function () {
        const KEY = 'livewind.appearance';
        const root = document.documentElement;
        const media = window.matchMedia('(prefers-color-scheme: dark)');

        function resolve(value) {
            return value === 'dark' || ((! value || value === 'system') && media.matches);
        }

        function apply(value) {
            root.classList.toggle('dark', resolve(value));
        }

        // Roda sincronamente no <head> para evitar flash antes da pintura.
        apply(localStorage.getItem(KEY));

        window.Livewind = window.Livewind || {};
        window.Livewind.appearance = {
            get() {
                return localStorage.getItem(KEY) || 'system';
            },
            set(value) {
                if (value === 'system') {
                    localStorage.removeItem(KEY);
                } else {
                    localStorage.setItem(KEY, value);
                }
                apply(value);
            },
            toggle() {
                this.set(resolve(this.get()) ? 'light' : 'dark');
            },
        };

        media.addEventListener('change', function () {
            if (window.Livewind.appearance.get() === 'system') {
                apply('system');
            }
        });
    })();
</script>
