<div id="divForm">
	<form id="editStateForm" name="editStateForm" method="post">
		<fieldset>
			<div class="formLine" style="width:100%; text-align:left">
				<div style="width:30%;float:left">* Nombre:</div>
                <input class="smallInput medium" name="name" id="name" type="text" value="{$post.name}" size="50"/>
				<hr />
            </div>            
            
			<div style="clear:both"></div>
			* Campos requeridos
            <div class="formLine" style="text-align:center; margin-left:300px">            
                <a class="button_grey" id="editState"><span>Actualizar</span></a>           
            </div>			
			<input type="hidden" id="type" name="type" value="saveEditState"/>
			<input type="hidden" id="stateId" name="stateId" value="{$post.stateId}"/>
		</fieldset>
	</form>
</div>
