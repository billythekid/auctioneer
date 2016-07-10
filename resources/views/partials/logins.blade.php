@if(Auth::guest())
    <div class="row">
        <div class="col-xs-12">
            <p>You can use the following login details to test this, or just make an account.</p>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <a class="btn btn-primary form-control" href="{{route('loginAs',1)}}">Log in
                    as {{\App\Models\User::find(2)->name ?? 'NO 2nd USER SET YET'}}</a>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <a class="btn btn-primary form-control" href="{{route('loginAs',2)}}">Log in
                    as {{\App\Models\User::find(3)->name ?? 'NO 3rd USER SET YET'}}</a>
            </div>
        </div>
        <div class="col-xs-12">
            <p>
                Ideally, you can log in using one set of details then open an incognito window or a different browser and log in
                using the other details to watch the live-updates on items.
            </p>
            <p>
                You can also make some duplicate tabs and see the "Users Online" number at the top change as you add and remove tabs.
            </p>
        </div>
    </div>
@endif