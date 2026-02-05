{{-- Theme CSS Variables - Include this in all pages' <head> section --}}
<style>
    :root {
        --theme-primary-color: {{ $themePrimaryColor ?? '#085952' }};
        --theme-secondary-color: {{ $themeSecondaryColor ?? '#0a7b73' }};
        --theme-gradient-start: {{ $themeGradientStart ?? '#076961' }};
        --theme-gradient-end: {{ $themeGradientEnd ?? '#0a7b6e' }};
        --theme-gradient-direction: {{ $themeGradientDirection ?? 'to right' }};
        --theme-hover-color: {{ $themeHoverColor ?? '#0f766e' }};
        --theme-button-color: {{ $themeButtonColor ?? '#0d9488' }};
        --theme-link-color: {{ $themeLinkColor ?? '#0d9488' }};
        --theme-gradient: {{ $themeGradientCss ?? 'linear-gradient(to right, #076961, #0a7b6e)' }};
        --theme-use-gradient: {{ ($themeUseGradient ?? false) ? '1' : '0' }};
    }
    
    /* Theme utility classes */
    .theme-primary { background-color: var(--theme-primary-color) !important; }
    .theme-secondary { background-color: var(--theme-secondary-color) !important; }
    .theme-gradient { background: var(--theme-gradient) !important; }
    .theme-link { color: var(--theme-link-color) !important; }
    .theme-link:hover { color: var(--theme-hover-color) !important; }
    .theme-button { background-color: var(--theme-button-color) !important; }
    .theme-button:hover { background-color: var(--theme-hover-color) !important; }
    
    /* Gradient buttons */
    .theme-button-gradient { background: var(--theme-gradient) !important; }
    .theme-button-gradient:hover { background: linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end)) !important; }
    
    /* Override Tailwind teal colors with theme colors */
    .bg-teal-900 { background-color: var(--theme-primary-color) !important; }
    .bg-teal-800 { background-color: var(--theme-hover-color) !important; }
    .bg-teal-700 { background-color: var(--theme-hover-color) !important; }
    .bg-teal-600 { background-color: var(--theme-link-color) !important; }
    .bg-teal-500 { background-color: var(--theme-link-color) !important; }
    .bg-teal-100 { background-color: rgba(13, 148, 136, 0.1) !important; }
    .bg-teal-50 { background-color: rgba(13, 148, 136, 0.05) !important; }
    .text-teal-500 { color: var(--theme-link-color) !important; }
    .text-teal-600 { color: var(--theme-link-color) !important; }
    .text-teal-700 { color: var(--theme-hover-color) !important; }
    .text-teal-800 { color: var(--theme-hover-color) !important; }
    .hover\:text-teal-500:hover { color: var(--theme-link-color) !important; }
    .hover\:text-teal-600:hover { color: var(--theme-link-color) !important; }
    .hover\:text-teal-700:hover { color: var(--theme-hover-color) !important; }
    .hover\:text-teal-800:hover { color: var(--theme-hover-color) !important; }
    .hover\:bg-teal-50:hover { background-color: rgba(13, 148, 136, 0.05) !important; }
    .hover\:bg-teal-100:hover { background-color: rgba(13, 148, 136, 0.1) !important; }
    .hover\:bg-teal-200:hover { background-color: rgba(13, 148, 136, 0.2) !important; }
    .hover\:bg-teal-600:hover { background-color: var(--theme-link-color) !important; }
    .hover\:bg-teal-700:hover { background-color: var(--theme-hover-color) !important; }
    .hover\:bg-teal-800:hover { background-color: var(--theme-hover-color) !important; }
    .border-teal-500 { border-color: var(--theme-link-color) !important; }
    .border-teal-600 { border-color: var(--theme-link-color) !important; }
    .hover\:border-teal-300:hover { border-color: rgba(13, 148, 136, 0.3) !important; }
    .hover\:border-teal-500:hover { border-color: var(--theme-link-color) !important; }
    .from-teal-500, .via-teal-600, .to-teal-500 { background: var(--theme-gradient) !important; }
    .from-teal-600, .to-teal-700 { background: var(--theme-gradient) !important; }
    .group:hover .from-teal-600, .group:hover .via-teal-700, .group:hover .to-teal-600 { 
        background: linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end)) !important; 
    }
    .hover\:from-teal-700:hover, .hover\:to-teal-800:hover { 
        background: linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end)) !important; 
    }
    .ring-teal-500 { --tw-ring-color: var(--theme-link-color) !important; }
    .focus\:ring-teal-500:focus { --tw-ring-color: var(--theme-link-color) !important; }
    .focus\:border-teal-500:focus { border-color: var(--theme-link-color) !important; }
</style>
<script>
    // Convert hex to RGB for opacity support
    (function() {
        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }
        
        // Set RGB values for opacity support
        const linkColor = '{{ $themeLinkColor ?? "#0d9488" }}';
        const rgb = hexToRgb(linkColor);
        if (rgb) {
            document.documentElement.style.setProperty('--theme-link-color-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
        }
    })();
</script>
