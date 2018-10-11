        <div class="control-group">
            <label class="control-label" for="tax_exempt_number">Tax exempt number:</label>
            <input type="hidden" name="user_data[tax_exempt_number]" value="N" />
            <div class="controls">            
				<input id="tax_exempt_number" name="user_data[tax_exempt_number]" size="25" maxlength="25" value="{$user_data.tax_exempt_number}" class="input-large" type="text">
            </div>
        </div>

		<div class="control-group">
			<label class="control-label" for="tax_entity_usecode">Tax entity / usecode:</label>
			<div class="controls">
			<select name="user_data[tax_entity_usecode]" id="tax_entity_usecode" class="span5" >				
				{foreach from=$tax_entity_usecode_data item="code" key="usecode"}
					<option value="{$code.usecode}" {if $code.usecode == $user_data.tax_entity_usecode}selected="selected"{/if} >{$code.usecode} - {$code.description}</option>
				{/foreach}
			</select>
			</div>
		</div>		