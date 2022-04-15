<form id="login-form" class="form" action="/NomameRafa/5" method="POST">
      
    
        <div class="form-group">
            <label for="nombre" class="text-info">Nombre:</label><br>
            <input type="text" id="typeEmailX" class="form-control form-control-lg" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="username" class="text-info">Correo Electronico:</label><br>
            <input type="email" id="typeEmailX" class="form-control form-control-lg" name="email" required>
        </div>
        <span style="color:red;">@error('email')
        {{$message}}
        @enderror
    </span>
        <div class="form-group">
            <label for="password" class="text-info">Contraseña:</label><br>
            <input class="form-control" type="password" id="typePasswordX" name="password" required>
        </div>
        <span style="color:red;">@error('password')
        {{$message}}
        @enderror
    </span>
        <div class="form-group">
            <label for="password" class="text-info">Confirmar contraseña:</label><br>
            <input type="password" id="typePasswordX" class="form-control" name="password_confirmation" required/>
        </div>
<br>
        <div class="form-group">
        <!-- The second value will be selected initially -->
        <select name="select" class="form-control form-control-lg" aria-label=".form-select-lg example">
            <option selected>Eligue un privilegio</option>
            <option value="1">Bajo privilegio</option>
            <option value="2">Medio Privilegio</option>
            <option value="3">Alto Privilegio</option>
          </select>
</div>


        <span style="color:red;">@error('password')
        {{$message}}
        @enderror
    </span>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Editar</button>
      </div>


        

    </form>