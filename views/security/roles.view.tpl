
<h1>
  Gestión de Roles
</h1>
<div class="row depth-1 m-padding">
  <form action="index.php?page=roles" method="post" class="col-md-8 col-offset-2">
      <div class="row s-padding">
        <label class="col-md-1" for="fltDsc">Roles:&nbsp;</label>
        <input type="text" name="fltDsc"  class="col-md-8"
              id="fltDsc" placeholder="Codigo de roles" value="{{fltDsc}}" />
        <button class="col-md-3" id="btnFiltro"><span class="ion-refresh">&nbsp;Actualizar</span></button>
      </div>
  </form>
</div>
<div class="row depth-1">
  <table class="col-md-12">
    <thead>
      <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th class="sd-hide">Estado</th>
        <th><a href="index.php?page=rol&rolescod&mode=INS" class="btn depth-1 s-margin">
          <span class="ion-plus-circled"></span>
          </a></th>
      </tr>
    </thead>
    <tbody class="zebra">
      {{foreach roles}}
      <tr>
        <td>{{rolescod}}</td>
        <td>{{rolesdsc}}</td>
        <td class="sd-hide">{{rolesest}}</td>
        <td class="center">
          <a href="index.php?page=rol&rolescod={{rolescod}}&mode=UPD" class="btn depth-1 s-margin"><span class="ion-edit"></span></a>
          <a href="index.php?page=rol&rolescod={{rolescod}}&mode=DSP" class="btn depth-1 s-margin"><span class="ion-eye"></span></a>
        </td>
      </tr>
      {{endfor roles}}
    </tbody>
  </table>
</div>
<script>
    $().ready(
    function(){
      $("#btnFiltro").click(
        function(e){
          e.preventDefault();
          e.stopPropagation();
          document.forms[0].submit();
        }
      );
    }

    );
</script>
