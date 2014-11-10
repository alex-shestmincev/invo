
{{ form("bikes/delete/" ~ bike.id, 'role': 'form') }}
    
    <h2>Are you sure? You wan't to delete bike {{ bike.title }} ({{ bike.description }}) ?</h2>
    <br/>
    <input type="hidden" name="confirm" value="Yes"/>
    <ul class="pager">
        <li class="previous pull-left">
            {{ link_to("bikes", "&larr; Go Back") }}
        </li>
        <li class="pull-right">
            {{ submit_button("Delete", "class": "btn btn-success") }}
        </li>
    </ul>

    {{ content() }}

</form>
