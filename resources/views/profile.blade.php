<div>
    @if(session('success'))
    <div style="color: greenyellow;">{{session('success')}}</div>
    @endif
    @if(session('error'))
    <div style="color: red;">{{session('error')}}</div>
    @endif
    <img width="200px" style="border-radius: 15px;" src="{{auth()->user()->avatar}}" alt=""><br>
    <input type="text" readonly value="{{auth()->user()->email}}"><br>
    <input type="text" readonly value="{{auth()->user()->name}}"><br>
    <input type="text" readonly value="{{auth()->user()->created_at}}">
    <br>
    <a href="/change-password" style="display: block;">Change password</a> <br>
    <input type="text" readonly value="{{auth()->user()->phone?auth()->user()->phone:'Not available'}}"><br>
    <a href="/add-phone" style="display: block;">Add/change phone number</a> <br>

    <input type="text" readonly value="{{auth()->user()->enable_2fa==1 ? '2FA enabled':'2FA disabled'}}"><br>
    @if(auth()->user()->enable_2fa==0)
    <a href="/2fa-send">Enable 2FA</a>
    @else
    <a href="/2fa-disable">Disable 2FA</a>
    @endif

    <br>
    <div style="display: flex;margin-top: 20px;">
        <a href="/" style="display: block;">&#8592;Back Home</a>
        <a href="/logout" style="display: block; color: red; margin-left: 10px;">Log out</a>
    </div>
</div>
