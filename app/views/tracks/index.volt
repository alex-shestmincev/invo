{{ content() }}

<ul class="pager">
    <li class="previous">
        {{ link_to("tracks", "&larr; Go Back") }}
    </li>
    <li class="next">
        {{ link_to("tracks/new", "Create track") }}
    </li>
</ul>

{% for tracks in page.items %}
    {% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Bike</th>
            <th>Title</th>
            <th>Distance</th>
            <th>User</th>
            <th>Date start</th>
            <th>Date finish</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>
    {% endif %}
        <tr>
            
            <td>{{ tracks.bike_id }}</td>
            <td>{{ tracks.title }}</td>
            <td>{{ tracks.distance }}</td>
            <td>{{ tracks.user_id }}</td>
            <td>{{ tracks.datestart }}</td>
            <td>
                {% if tracks.dateend %}
                    {{ tracks.dateend}}
                {% else %}
                    {{ link_to("tracks/finish/" ~ tracks.id, "Finish") }}
                {% endif %}
            </td>
            <td width="7%">{{ link_to("tracks/edit/" ~ tracks.id, '<i class="glyphicon glyphicon-edit"></i> Edit', "class": "btn btn-default") }}</td>
            <td width="7%">{{ link_to("tracks/delete/" ~ tracks.id, '<i class="glyphicon glyphicon-remove"></i> Delete', "class": "btn btn-default") }}</td>
        </tr>
    {% if loop.last %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="6" align="right">
                <div class="btn-group">
                    {{ link_to("tracks/index", '<i class="icon-fast-backward"></i> First', "class": "btn") }}
                    {{ link_to("tracks/index?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn") }}
                    {{ link_to("tracks/index?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn") }}
                    {{ link_to("tracks/index?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn") }}
                    <span class="help-inline">{{ page.current }} of {{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    </tbody>
</table>
    {% endif %}
{% else %}
    No tracks are recorded
{% endfor %}
