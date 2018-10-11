		<div class="control-group">
			<label class="control-label" for="elm_tax_code">Tax Code:</label>
			<div class="controls">
				<input type="text" name="product_data[tax_code]" id="elm_tax_code" size="11" maxlength="11"  value="{$product_data.tax_code|default:"none"}" class="input-small" />
			</div>
		</div>

       <div class="control-group">
            <label class="control-label" for="elm_upc_code">UPC Code:</label>
            <div class="controls">
                <input type="text" name="product_data[upc_code]" id="elm_upc_code" size="11" maxlength="20"  value="{$product_data.upc_code|default:"none"}" class="input-small" />
            </div>
        </div>