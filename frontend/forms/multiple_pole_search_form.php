<div class="card p-4">
  <p class="text  mb-0 fw-light ">
    <strong class="me-2">
      Search Option 1:
    </strong>
    <small>Search multiple poles by pole numbers listed in an excel file.</small>
    <small class="my-3 d-block">
      <a href="<?php echo SCJPC_URL . "frontend/ExamplePoleNumbers.xlsx"; ?>" download>
        Example of Pole Numbers Excel file - (.xlsx)
      </a>
    </small>
    <small class="my-3 d-block">
      Click "Choose File" button, select an Excel file, and click "Search multiple poles" button (one time only).
    </small>
  </p>
  <!--  <form class="needs-validation" id="multiple_pole_search" method="post" enctype="multipart/form-data" novalidate>-->
  <form class="needs-validation" id="multiple_pole_search" method="post" novalidate>

    <div class="row custom-border rounded-1 p-4">
      <div class="col-6 d-flex flex-column">
        <div class="mb-3">
          <label for="excel_contains_header" class="form-label d-block">Does Excel/CSV contains Header?</label>
          <input type="checkbox" name="contains_header"  id="excel_contains_header" <?php echo isset($_POST['contains_header']) && $_POST['contains_header'] ? 'checked' : ''; ?> />
        </div>
        <div class="mb-3">
          <label for="id_search_file" class="form-label">Select File</label>
          <input class="form-control" name="uploaded_file" type="file" id="id_search_file" accept=".xlsx"/>
        </div>
        <p class="text  mb-3 fw-light ">
          <strong class="me-2">
            Search Option 2:
          </strong>
          <small>Search poles by owner code</small>
          <small class="my-1 d-block">
            Select an owner code and click "Search multiple poles" button.
          </small>
        </p>
        <div class="mb-3">
          <label for="base_owner" class="form-label">OR, Select Code</label>
          <select class="form-select" id="base_owner" name="base_owner" aria-label="Default select example">
            <option value=""></option>
            <?php foreach (BASE_OWNERS as $key => $value) { ?>
              <option <?php echo isset($_POST['base_owner']) && $_POST['base_owner'] == $key ? 'selected' : '' ?>
                value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>            </p>
120
 
            <p class="col-6 ps-3 m-0">
121
 
              <input type="text" name="location" class="form-control" id="location"
122
 
                    value="<?php echo $_REQUEST['location'] ?? ''; ?>"/>
123
 
            </p>
124
 
          </div>
125
 
        </div>
126
 
      </div>
127
 
      <div class="row">
128
 
        <div class="mb-3 col-4">
129
 
          <label for="id_latitude" class="form-label">Latitude</label>
130
 
          <input type="text" name="latitude" class="form-control" id="id_latitude" aria-describedby="emailHelp"
131
 
                value="<?php echo $_REQUEST['latitude'] ?? ''; ?>"/>
132
 
        </div>
133
 
        <div class="mb-3 col-4">
134
 
          <label for="id_longitude" class="form-label">Longitude</label>
135
 
          <input type="texCan you remove these froum our virtual cards/
136
 
t" name="longitude" class="form-control" id="id_longitude" aria-describedby="emailHelp"
137
 
                value="<?php echo $_REQUEST['longitude'] ?? ''; ?>"/>
138
 
        </div>
139
 
        <div class="mb-4 col-4">
140
 
          <label for="id_distance" class="form-label">Distance</label>
141
 
          <select class="form-select" id="id_distance" name="distance" aria-label="Default select example">
142
 
            <?php foreach (DISTANCES as $key => $value) { ?>
143
 
              <?php $selected_distance = !empty($_REQUEST["distance"]) && $_REQUEST["distance"] == $key ? 'selected="selected"' : ''; ?>
144
 
              <option value="<?php echo $key; ?>" <?php echo $selected_distance ?>><?php echo $value; ?></option>
145
 
            <?php } ?>
146
 
          </select>
147
 
        </div>
148
 
      </div>
149
 
      <div class="d-flex justify-content-between">
150
 
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
151
 
        <button type="submit" class="btn btn-primary">Search</button>
152
 
      </div>
153
 
      <input type="hidden" id="action" name="action" value="advanced_pole_search"/>
154
 
      <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
155
 
      <input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
156
 
      <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
157
 
      <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'unique_id'; ?>"/>
158
 
      <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_order'] ?? 'asc'; ?>"/>
159
 
      <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
160
 
    </form>
161
 
>>>>>>> master
162
​
163
    <div class="accordion mt-5" id="accordionPanelsStayOpenExample">
164
      <div class="accordion-item">
165
        <p class="accordion-header" id="panelsStayOpen-headingThree">
166
          <button class="accordion-button collapsed rounded-0" type="button" data-bs-toggle="collapse"
167
                  data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
168
                  aria-controls="panelsStayOpen-collapseThree">
169
            Search Instructions
170
          </button>
171
        </p>
172
        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
173
            aria-labelledby="panelsStayOpen-headingThree">
174
          <div class="accordion-body input-bg">
175
            <small>Use this search when pole numbers or locations are partially known. Search will return a list of all
176
              records found in the database, dead or active. O<span style="line-height: 20.8px;">mit space, slash, hyphen, and other special characters.&nbsp;</span><br
177
                style="line-height: 20.8px;">
178
              To search:&nbsp;<br>
179
              1. Select a category from&nbsp;the drop-down list.&nbsp;<br>
180
              2. Type partial pole number and/or location in the respective search boxes. If location is unknown (left
181
              blank), you must enter at least 3 characters in the&nbsp;pole number search box.<br>
182
              3. Click Search.&nbsp;•
183
              4. Click on column heading to sort the list.&nbsp;<br>
184
              5. Click the unique ID to view the record.<br>
185
              Tips&nbsp;<br>
186
              Users can use the percent symbol (%) as a wildcard to replace missing numbers (e.g. 456%17E) or locations
187
              (e.g. LA BREA%FAIRVIEW).&nbsp;<br>
188
              The search will return all records (dead or active records). If the complete <strong>pole number is known
189
                and only the current active record is needed</strong>, use the Quick Pole Search page for faster search
190
              performance.</small>
191
          </div>
192
        </div>
193
      </div>
194
    </div>
195
  </div>
196
</div>
197
<div class="response-table"></div>
198

          </select>
        </div>

        <input type="hidden" id="action" name="action" value="multiple_pole_search" />
        <input type="hidden" id="per_page" name="per_page" value="<?php echo $_POST['per_page'] ?? '50'; ?>" />
        <input type="hidden" id="page_number" name="page_number" value="<?php echo $num_results_on_page ?? '1'; ?>" />
        <input type="hidden" id="last_id" name="last_id" value="<?php echo $_POST['last_id'] ?? ''; ?>" />
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
        <div class="d-flex mt-auto justify-content-between">
          <button type="button" class="clearBtn btn btn-secondary">Clear</button>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
      <div class="col-6">
        <?php include_once SCJPC_PLUGIN_FRONTEND_BASE . 'forms/multiple_pole_search_columns_form.php' ?>
      </div>
    </div>
  </form>
</div>
<div class="response-table"></div>
