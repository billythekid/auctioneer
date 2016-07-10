@if(Auth::guest())
    <div class="row">
        <div class="col-xs-12">
            <p>You can use the following login details to test this, or just make an account.</p>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-primary" href="{{route('loginAs',1)}}">Log in as {{\App\Models\User::find(2)->name ?? 'NO 2nd USER SET YET'}}</a>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-primary" href="{{route('loginAs',2)}}">Log in as {{\App\Models\User::find(3)->name ?? 'NO 3rd USER SET YET'}}</a>
        </div>
        <div class="col-xs-12">
            <p>
                Ideally, you can log in using one set of details then open an incognito window or a different browser and log in
                using the other details to watch the live-updates on items.
            </p>
        </div>
    </div>
@endif