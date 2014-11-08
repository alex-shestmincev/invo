
{{ form("user/delete/" ~ user.id, 'role': 'form') }}
    
    <h2>Are you sure? You wan't to delete user {{ user.name }} ({{ user.email }}) ?</h2>
    <br/>
    <input type="hidden" name="confirm" value="Yes"/>
    <ul class="pager">
        <li class="previous pull-left">
            {{ link_to("user", "&larr; Go Back") }}
        </li>
        <li class="pull-right">
            {{ submit_button("Delete", "class": "btn btn-success") }}
        </li>
    </ul>

    {{ content() }}

</form>
