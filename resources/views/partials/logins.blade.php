@if(Auth::guest())
    <div class="row">
        <div class="col-xs-12">
            <p>You can use the following login details to test this, or just make an account.</p>
        </div>
        <div class="col-sm-6">
            <ul>
                <li>test@example.com</li>
                <li>secret</li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul>
                <li>example@example.com</li>
                <li>secret</li>
            </ul>
        </div>
        <div class="col-xs-12">
            <p>
                Ideally, you can log in using one set of details then open an incognito window or a different browser and log in
                using
                the other details to watch the live-updates on items.
            </p>
        </div>
    </div>
@endif