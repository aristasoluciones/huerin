{foreach from=$item.tasks item=task key=key}
	<tr id="1">
		<td align="center" class="id">{$task.nombreTask}</td>
		<td align="center">Dia {$task.diaVencimiento}</td>  
		<td align="center">{$task.prorroga} Dias</td>  
    <td align="center">{$task.control}</td>
    <td align="center">{$task.control2}</td>
    <td align="center">{$task.control3}</td>
		<td align="center">        	
      	<img src="{$WEB_ROOT}/images/icons/action_delete.gif" class="spanTaskDelete" id="{$task.taskId}" title="Desactivar"/> 
        <img src="{$WEB_ROOT}/images/icons/edit.gif" class="spanTaskEdit" id="{$task.taskId}" title="Editar"/>
		</td>
	</tr>
{foreachelse}
<tr><td colspan="4" align="center">No se encontr&oacute; ning&uacute;n registro.</td></tr>
{/foreach}
