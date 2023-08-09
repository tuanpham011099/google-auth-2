<div>
    <form action="/2fa-verification" method="post">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="code">Enter code 2FA sms</label>
        <input type="text" name="code">
        <button type="submit">Verify</button>
    </form>

</div>
