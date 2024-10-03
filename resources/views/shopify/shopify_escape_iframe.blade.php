<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <script src="https://unpkg.com/@shopify/app-bridge@2.0.0"></script>
</head>
<body>
    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var createApp = AppBridge.default;
        var Redirect = AppBridge.actions.Redirect;

        var app = createApp({
            apiKey: '{{ config("shopify.client_id") }}',
            shopOrigin: '{{ request()->get("shop") }}',
            forceRedirect: true
        });

        var redirect = Redirect.create(app);
        redirect.dispatch(Redirect.Action.REMOTE, "{{ $redirectUri }}");
    </script>
</body>
</html>
