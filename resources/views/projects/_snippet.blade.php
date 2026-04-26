<script>
    (function(w, d, u, k) {
        w.st = w.st || function() { (w.st.q = w.st.q || []).push(arguments) };
        var s = d.createElement('script'); s.async = true; s.src = u + '?key=' + k;
        var h = d.getElementsByTagName('script')[0]; h.parentNode.insertBefore(s, h);
    })(window, document, '{{ url('/js/track.js') }}', '{{ $project->tracking_id }}');
</script>
