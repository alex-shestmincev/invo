
{{ content() }}

{{ form("user/index","id":"searchUsersForm","class":"searchform") }}

<h2>Search users</h2>

<fieldset>

{% for element in form %}
    {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
{{ element }}
    {% else %}
<div class="control-group">
    <div class="controls">
        {{ element.label(['class': 'control-label']) }}
        {{ element }}
    </div>
</div>
    {% endif %}
<br/>
{% endfor %}

<div class="control-group">
    {{ submit_button("Search", "class": "btn btn-primary") }}
</div>
<br/>

</fieldset>

</form>


{% for user in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Active</th>
            <th>Level</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.created_at }}</td>
            <td>{{ user.active }}</td>
            <td>{{ user_roles[user.level] }}</td>
            <td width="7%">{{ link_to("user/edit/" ~ user.id, '<i class="glyphicon glyphicon-edit"></i> Edit', "class": "btn btn-default") }}</td>
            <td width="7%">{{ link_to("user/delete/" ~ user.id, '<i class="glyphicon glyphicon-remove"></i> Delete', "class": "btn btn-default") }}</td>
        </tr>
    </tbody>
{% if loop.last %}
    <tbody>
        <tr>
            <td colspan="9" align="right">
                <div class="btn-group">
                    {{ link_to("user/index", '<i class="icon-fast-backward"></i> First', "class": "btn btn-default page") }}
                    {{ link_to("user/index?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn btn-default page") }}
                    {{ link_to("user/index?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn btn-default page") }}
                    {{ link_to("user/index?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn btn-default page") }}
                    &nbsp; <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    <tbody>
</table>
{% endif %}
{% else %}
    No companies are recorded
{% endfor %}
