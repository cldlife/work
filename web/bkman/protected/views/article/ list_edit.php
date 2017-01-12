<!-- <select id="category">
<option value="0">请选择</option>
<?php foreach ($category as $id => $item) :?>
<option value="<?php echo $id?>" <?php if ($id == $categoryid) echo " selected";?>><?php echo $item?></option>
<?php endforeach;?>
</select> -->
<!-- <input type="text" class="txt" style="width:160px;" id="keyword" name="keyword" value="<?php echo $keyword;?>" placeholder="查询标题或内容..."> -->
<a class="confirm_btn" id="btn-search-a"><span>&nbsp;查询&nbsp;</span></a>


 <tr>
            <th width="10%" style="text-align:center">类别</th>
            <td>
                 <select id="category_id" name="category_id">
                    <option value="0">请选择</option>
                    <?php foreach ($category as $id => $name) :?>
                  <option value="<?php echo $id?>" <?php if ($id == $article['category_id']) echo " selected";?>><?php echo $name?></option>
                  <?php endforeach;?>
                 </select>
            </td>
          </tr>