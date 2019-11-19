<h1>Gestión de Rol</h1>
<div class="row depth-1 m-padding">
  <h2>{{modeDesc}}</h2>
</div>
<div class="row depth-1 m-padding">
  {{if haserrores}}
    <ul class="alert alert-danger depth-1 m-padding" style="list-style:none;">
      {{foreach errores}}
        <li>
          {{this}}
        </li>
      {{endfor errores}}
    </ul>
  {{endif haserrores}}
  <form action="index.php?page=rol" method="post" class="col-sm-8 col-sm-offset-2 col-md-6 col-offset-3">
    <input type="hidden" name="mode" value="{{mode}}"  />
    <input type="hidden" name="tocken" value="{{tocken}}"  />
    <input type="hidden" name="rolescod" value="{{rolescod}}"  />
    <div class="row">
      <div class="row s-padding">
        <label class="col-sm-5">Código del rol </label>
        <input class="col-sm-7" {{readonly}} {{isupdate}} type="text" name="txtCodigo" id="txtCodigo" value="{{rolescod}}" placeholder="Codigo" />
      </div>
    <div class="row s-padding">
      <label class="col-sm-5">Descripción</label>
      <input class="col-sm-7" {{readonly}} type="text" name="txtName" id="txtName" value="{{rolesdsc}}" placeholder="Descripcion del Rol" />
    </div>
    <div class="row s-padding">
      <label class="col-sm-5">Estado</label>
      <span class="select col-sm-7"><select {{if readonly}}disabled readonly="readonly" {{endif readonly}} class="col-md-12" id="cmbEstado" name="cmbEstado">
        {{foreach estadoRol}}
          <option value="{{codigo}}" {{selected}}>{{valor}}</option>
        {{endfor estadoRol}}
      </select> </span>
    </div>

    <div class="row s-padding">
      <div class="col-md-12 right">
        {{ifnot readonly}}
        <button id="btnConfirm"><span class="icon "></span>Confirmar</button>
        {{endifnot readonly}}
        <button id="btnCancel">Cancelar</button>
      </div>
    </div>
    </div>
  </form>
</div>
{{ifnot isinsert}}
<div class="row depth-1 m-padding">
  <h2>Programas Por Rol</h2>
</div>
<div class="row depth-1 m-padding">
  <table class="col-sm-8 col-sm-offset-2 col-md-6 col-offset-3">
    <thead>

      <tr>
          <th colspan="3">
            <form action="index.php?page=rol" method="post"  id="frmAddPrg">
            {{ifnot readonly}}
              <span class="select col-sm-10">
                <select name="programacod" class="col-sm-12">
                  {{foreach prgavailable}}
                    <option value="{{fncod}}">{{fndsc}}</option>
                  {{endfor prgavailable}}
                </select>
              </span>
              <input type="hidden" name="rolescod" value="{{rolescod}}"  />
              <input type="hidden" name="btnAddPgm" value="AddPrg"  />
              <input type="hidden" name="mode" value="{{mode}}"  />
              <input type="hidden" name="tocken" value="{{tocken}}"  />
            {{endifnot readonly}}
            {{if readonly}}
              Programas
            {{endif readonly}}
            {{ifnot readonly}}
            <span class="col-sm-2 right">
            <a href id="btnAddPgm" class="btn depth-1 s-margin">
              <span class="ion-plus-circled"></span>
            </a>
            </span>
            {{endifnot readonly}}
            {{if readonly}}
            &nbsp;
            {{endif readonly}}
            </form>
          </th>
      </tr>

    </thead>
    <tbody>
      {{foreach prgassign}}
      <tr>
        <td>
          {{fncod}}
        </td>
        <td>
          {{fndsc}}
        </td>
        <td class="right">
          {{ifnot readonly}}
          <form action="index.php?page=rol" method="post">
            <input type="hidden" name="programacod" value="{{fncod}}"  />
            <input type="hidden" name="rolescod" value="{{rolescod}}"  />
            <input type="hidden" name="mode" value="{{mode}}"  />
            <input type="hidden" name="tocken" value="{{tocken}}"  />
            <input type="hidden" name="btnDelPgm" value="DelPrg"  />
            <a href id="btnDelPgm" class="btn depth-1 s-margin">
              <span class="ion-minus-circled"></span>
            </a>
          </form>
          {{endifnot readonly}}
          {{if readonly}}
            &nbsp;
          {{endif readonly}}
        </td>
      </tr>
      {{endfor prgassign}}
    </tbody>
  </table>
</div>
{{endifnot isinsert}}
<script>
  $().ready(function(){
      $("#btnConfirm").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        document.forms[0].submit();
        });
      $("#btnCancel").click(function(e){
          e.preventDefault();
          e.stopPropagation();
          window.location.assign("index.php?page=roles");
          });

    {{ifnot isinsert}}
    $("#btnAddPgm").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#frmAddPrg").submit();
      });
    $("#btnDelPgm").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).parent("form").submit();
      });
    {{endifnot isinsert}}
    });

</script>
