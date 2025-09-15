@extends("cafeapp.layouts._theme", ["title" => $title])

<span></span>
@section("content")
<div class="app_formbox app_widget">
    <form class="app_form" action="{{ url('/app/profile') }}" method="post">
        @csrf
        @method("PUT")
        <input type="hidden" name="update" value="true"/>
        <div class="app_formbox_photo">
            <div class="rounded j_profile_image thumb" style="background-image: url('{{ $photo }}')"></div>
            <div><input data-image=".j_profile_image" type="file" class="radius"  name="photo"/></div>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-user">Nome:</span>
                <input class="radius" type="text" name="first_name" required
                       value="{{ $user->first_name }}"/>
            </label>

            <label>
                <span class="field icon-user-plus">Sobrenome:</span>
                <input class="radius" type="text" name="last_name" required
                       value="{{ $user->last_name }}"/>
            </label>
        </div>

        <label>
            <span class="field icon-briefcase">Genero:</span>
            <select name="gender" required>
                <option value="">Selecione</option>
                <option {{ ($user->gender == 'man' ? 'selected' : '') }} value="man">&ofcir; Masculino</option>
                <option {{ ($user->gender == 'woman' ? 'selected' : '') }} value="woman">&ofcir; Feminino</option>
                <option {{ ($user->gender == 'other' ? 'selected' : '') }} value="other">&ofcir; Outro</option>
            </select>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-calendar">Nascimento:</span>
                <input class="radius mask-date" type="text" name="datebirth" placeholder="dd/mm/yyyy" required
                       value="{{ ($user->datebirth ? $user->datebirth->format('d/m/Y') : null) }}"/>
            </label>

            <label>
                <span class="field icon-briefcase">Número do bilhete:</span>
                <input class="radius mask-doc" type="text" name="document" placeholder="Apenas números" required
                       value="{{ $user->document }}"/>
            </label>
        </div>

        <label>
            <span class="field icon-envelope">E-mail:</span>
            <input class="radius" type="email" name="email" placeholder="Seu e-mail de acesso" readonly
                   value="{{ $user->email }}"/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-unlock-alt">Senha:</span>
                <input class="radius" type="password" name="password" placeholder="Sua senha de acesso"/>
            </label>

            <label>
                <span class="field icon-unlock-alt">Repetir Senha:</span>
                <input class="radius" type="password" name="password_confirmation" placeholder="Sua senha de acesso"/>
            </label>
        </div>

        <div class="al-center">
            <div class="app_formbox_actions">
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>

@endsection