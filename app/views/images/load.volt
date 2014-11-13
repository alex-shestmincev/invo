<div class="fL" id="edit_image_{{ image.id }}" >
{{ link_to(image.link_big, image(image.link_min)) }} 
&nbsp;
<br/>
<a href="#" class="del_img" onclick="deleteimg('{{ image.id }}');return false;" style="color:red"> <b>x</b> </a>
</div>