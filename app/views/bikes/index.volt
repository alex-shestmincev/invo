{{ content() }}

<ul class="pager">
    <li class="previous">
        {{ link_to("bikes", "&larr; Go Back") }}
    </li>
    <li class="next">
        {{ link_to("bikes/new", "Create bike") }}
    </li>
</ul>

{% for bikes in page.items %}
    {% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Description</th>
            <th>Distance</th>
            <th>Key</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>
    {% endif %}
        <tr>
            <td>
            {% if images[bikes.id] is defined %}
                {{ image(images[bikes.id])  }}
            {% endif %}
            </td>
            <td>{{ bikes.title }}</td>
            <td>{{ bikes.description }}</td>
            <td>{{ bikes.distance }}</td>
            <td>{{ bikes.key }}</td>
            <td width="7%">{{ link_to("bikes/edit/" ~ bikes.id, '<i class="glyphicon glyphicon-edit"></i> Edit', "class": "btn btn-default") }}</td>
            <td width="7%">{{ link_to("bikes/delete/" ~ bikes.id, '<i class="glyphicon glyphicon-remove"></i> Delete', "class": "btn btn-default") }}</td>
        </tr>
    {% if loop.last %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="6" align="right">
                <div class="btn-group">
                    {{ link_to("bikes/index", '<i class="icon-fast-backward"></i> First', "class": "btn") }}
                    {{ link_to("bikes/index?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn") }}
                    {{ link_to("bikes/index?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn") }}
                    {{ link_to("bikes/index?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn") }}
                    <span class="help-inline">{{ page.current }} of {{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    </tbody>
</table>
    {% endif %}
{% else %}
    No bikes are recorded
{% endfor %}
