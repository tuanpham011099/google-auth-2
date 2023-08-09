<div>
    <form method="POST" action="{{url('add-phone')}}">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="phone" style="display: block;" >Enter your phone number</label>
        <input type="text" name="phone" value="{{auth()->user()->phone}}" required>
        <button type="submit">Add</button>
    </form>

    @if(session('success'))
    <div style="color: greenyellow;">{{session('success')}}</div>
    @endif
    @if(session('error'))
    <div style="color: red;">{{session('error')}}</div>
    @endif
    <form method="POST" action="{{url('phone-verification')}}">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="phone" style="display: block;" >Enter code</label>
        <input type="text" name="code" required>
        <button type="submit">Add</button>
    </form>
    <div style="display: block;margin-top: 10px;">
        <a href="/profile">Back</a>
    </div>
</div>
