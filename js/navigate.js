    $(function()
    {
        // Prevent accidental navigation away
        $(':input').bind('change', function() { setConfirmUnload(true); });
        $('.noprompt-required').click(function() { setConfirmUnload(false); });

        function setConfirmUnload(on)
        {
            window.onbeforeunload = on ? unloadMessage : null;
        }
        function unloadMessage()
        {
            return ('You have entered new data on this page. ' +
            'If you navigate away from this page without ' +
            'first saving your data, the changes will be lost.');
        }

        window.onerror = UnspecifiedErrorHandler;
        function UnspecifiedErrorHandler()
        {
            return true;
        }

    });

