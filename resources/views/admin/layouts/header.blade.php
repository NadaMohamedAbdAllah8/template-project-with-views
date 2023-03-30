<div style="text-align: right;margin-top:20px;height: 10%;">
    <span style="margin-top:20px;padding:auto;
        display: inline-block;">
        <span style="color: rgba(128, 128, 128, 0.689);"> Logged in as</span>
        <?php echo //$admin_name
            Auth::guard('admin')->user()->name; ?>
    </span>

    <form id="logout_form" action="{{ route('admin.logout') }}" method="post"
        style="display: inline;margin:8px;padding:2px;">
        @csrf
        <a href="javascript:{}" onclick="document.getElementById('logout_form').submit();" class="actionbtn"
            style="width:7%;margin:8px;">
            log out
        </a>
    </form>
</div>
