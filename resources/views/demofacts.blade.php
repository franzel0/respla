<div class="col-md-10 col-md-offset-1">
    <hr>
    @if(Storage::exists('democompany.json'))
        <h2>Company: {{json_decode(Storage::get('democompany.json'), true)['name']}}</h2>
    @endif
    @if(Storage::exists('demousers.json'))
        <h2>Benutzer</h2>
        <table class="table table-bordered table-striped">
            <tr>
                <th>Name</th>
                <th>Rolle</th>
                <th>Passwort</th>
            </tr>
            @foreach(json_decode(Storage::get('demousers.json')) as $u)
                <tr>
                    <td>{{$u->name}}</td>
                    <td>{{\App\User::find($u->id)->roles->first()->name}}</td>
                    <td>secret</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
