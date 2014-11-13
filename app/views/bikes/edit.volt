
{{ form("bikes/save", 'role': 'form') }}

    <ul class="pager">
        <li class="previous pull-left">
            {{ link_to("bikes", "&larr; Go Back") }}
        </li>
        <li class="pull-right">
            {{ submit_button("Save", "class": "btn btn-success") }}
        </li>
    </ul>

    {{ content() }}

    <h2>Edit bike</h2>

    <fieldset>

        {% for element in form %}
            {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
                {{ element }}
            {% else %}
                <div class="form-group">
                    {{ element.label() }}
                    {{ element.render(['class': 'form-control']) }}
                </div>
            {% endif %}
        {% endfor %}

    </fieldset>

</form>



{{ form("images/load/1/" ~ bike.id, "method": "post", "id": "imageform", "enctype": "multipart/form-data") }}
        
<div  id='div_for_form' class='div_for_img_forms'>  Add photo 
<div align="center" id='td_for_load_img1' >
    <div class="ins_file_text brad15" title="Add photo ">
        <div> <div class="sprite add_photo"></div></div>
        <input accept='image/jpeg' type='file' name='photoimg[]' multiple class="ins_file_input" id='photoimg' size="1" style="margin-top: 0px; margin-left:-410px; -moz-opacity: 0; filter: alpha(opacity=0); opacity: 0; font-size: 150px; height: 100px;"/>
    </div>
</div>



<br/>	
</div>
</form>	
<div id="preview" class="fL">
    {% for image in images %}
        {{ partial("images/load") }}
    {% endfor %}
</div>
<div id="preview1" class="fL"></div>
<div class="cB"></div>
