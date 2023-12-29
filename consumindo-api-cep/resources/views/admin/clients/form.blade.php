<div class="form-group {{ $errors->has('nome') ? 'has-error' : ''}}">
    <label for="nome" class="control-label">{{ 'Nome' }}</label>
    <input class="form-control" name="nome" type="text" id="nome" value="{{ isset($client->nome) ? $client->nome : ''}}" >
    {!! $errors->first('nome', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label">{{ 'Email' }}</label>
    <input class="form-control" name="email" type="email" id="email" value="{{ isset($client->email) ? $client->email : ''}}" >
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cep') ? 'has-error' : ''}}">
    <label for="cep" class="control-label">{{ 'Cep' }}</label>
    <input class="form-control" name="cep" type="text" id="cep" data-mask="00000-000" placeholder="00000-000" value="{{ isset($client->cep) ? $client->cep : ''}}" >
    {!! $errors->first('cep', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('logradouro') ? 'has-error' : ''}}">
    <label for="logradouro" class="control-label">{{ 'Logradouro' }}</label>
    <input class="form-control" name="logradouro" type="text" id="logradouro" value="{{ isset($client->logradouro) ? $client->logradouro : ''}}" >
    {!! $errors->first('logradouro', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('bairro') ? 'has-error' : ''}}">
    <label for="bairro" class="control-label">{{ 'Bairro' }}</label>
    <input class="form-control" name="bairro" type="text" id="bairro" value="{{ isset($client->bairro) ? $client->bairro : ''}}" >
    {!! $errors->first('bairro', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('cidade') ? 'has-error' : ''}}">
    <label for="cidade" class="control-label">{{ 'Cidade' }}</label>
    <input class="form-control" name="cidade" type="text" id="cidade" value="{{ isset($client->cidade) ? $client->cidade : ''}}" >
    {!! $errors->first('cidade', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('estado') ? 'has-error' : ''}}">
    <label for="estado" class="control-label">{{ 'Estado' }}</label>
    <input class="form-control" name="estado" type="text" id="estado" value="{{ isset($client->estado) ? $client->estado : ''}}" >
    {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
