var vtws = false;
var vtgears = new Vtiger_Gears();

var vtoffline = {
	appid : 'vtoffline',
	dbname : 'vtoffline',

	hasLoggedIn : false,
	getTemplateURL: function(jstpl) {
		return ('js/templates/' + jstpl);
	},
	aliveCheckInterval: 1500, // milliseconds
	isAliveIntervalId : false,
	isAlive : false,

	syncInterval : 2000, // milliseconds
	syncIntervalId : false,
	syncing        : false,

	modules  : false,
	describe : false,
	modcols  : false,

	dbschema : [
	//'DROP TABLE IF EXISTS vtoffline_app',
	//'DROP TABLE IF EXISTS vtoffline_crmentity',
	//'DROP TABLE IF EXISTS vtoffline_crmrecord',
	//'DROP TALBE IF EXISTS vtoffline_describe',
	'CREATE TABLE IF NOT EXISTS vtoffline_app(version VARCHAR(5))',
	'CREATE TABLE IF NOT EXISTS vtoffline_crmentity(gearid INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,recordid INTEGER,module VARCHAR(30),status VARCHAR(30))',
	'CREATE TABLE IF NOT EXISTS vtoffline_crmrecord(gearid INTEGER NOT NULL, fieldname VARCHAR(100), fieldvalue TEXT)',
	'CREATE TABLE IF NOT EXISTS vtoffline_describe (describeid INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, module VARCHAR(30), content TEXT)'
	]
};

/** Utility Functions **/
vtoffline.implode = function(delim, values, usevalue) {
	var retstring = '';
	for(var index = 0; index < values.length; ++index) {
		var value = (usevalue != null)? usevalue : values[index];
		retstring += value;
		if(index != values.length-1)
			retstring += delim;
	}
	return retstring;
}
vtoffline.formToMap = function(form) {
	var frmfields = form.getElementsByTagName('*');
	var frmmap = {};
	for(var idx=0; idx < frmfields.length; ++idx) {
		var frmfield = frmfields[idx];
		if(frmfield.nodeName == 'INPUT') {
			if(frmfield.type == 'text' || frmfield.type == 'hidden') {
				frmmap[frmfield.name] = frmfield.value;
			} else if(frmfield.type == 'checkbox' && frmfield.checked) {
				frmmap[frmfield.name] = frmfield.value;
			}
		}
	}
	return frmmap;
}
	
/* Check Login */
vtoffline.checkLogin = function() {
	return (vtws && vtws._userid);
}
/* Check Server is Alive */
vtoffline.cancelPingServer = function() {
	if(vtoffline && vtoffline.isAliveIntervalId) {
		window.clearInterval(vtoffline.isAliveIntervalId);
		vtoffline.isAliveIntervalId = false;
	}
}

vtoffline.SetupTimers = function() {
	window.setTimeout(vtoffline.pingServer, 500);
	window.setTimeout(vtoffline.setupSync, 1000); 
}

vtoffline.pingServer = function() {
	vtgears.checkOffline('index.php', vtoffline.pingServerCallback);
}
vtoffline.pingServerCallback = function(isalive) {
	vtoffline.isAlive = isalive;
	var domnode = document.getElementById('vtoffline-mode');
	if(domnode) {
	   if(isalive) domnode.innerHTML = ' <font color="green">Online</font>';
	   else domnode.innerHTML = ' <font color="red">Offline</font>';
	}
	if(!vtoffline.isAliveIntervalId) {
		vtoffline.isAliveIntervalId = window.setInterval(vtoffline.pingServer, vtoffline.aliveCheckInterval);
	}
}

vtoffline.setupSync = function() {
	if(!vtoffline.syncIntervalId) {
		vtoffline.syncIntervalId = window.setInterval(vtoffline.doSync, vtoffline.syncInterval);
	}
}
vtoffline.cancelSync = function() {
	if(vtoffline.syncIntervalId) {
		window.clearInterval(vtoffline.syncIntervalId);
		vtoffline.syncIntervalId = false;
	}
}
vtoffline.doSync = function() {
	// Syncing is in progress, try later.
	if(vtoffline.syncing) return;

	// If connection is not alive we cannot perform sync.
	if(!vtoffline.isAlive) {
		vtoffline.syncing = false;
		vtoffline.hideProgressBar();
		return;
	}

	vtoffline.syncing = true;
	vtoffline.showProgressBar('Syncing...');

	var rs = vtgears.executeSQL("SELECT * FROM vtoffline_crmentity WHERE status='offline' LIMIT 1");
	if(rs.isValidRow()) {
		var gearid = rs.fieldByName('gearid');
		var module = rs.fieldByName('module');
		var rs2 = vtgears.executeSQL("SELECT * FROM vtoffline_crmrecord WHERE gearid = " + gearid);
		var valuesmap = {};
		while(rs2.isValidRow()) {
			valuesmap[rs2.fieldByName('fieldname')] = rs2.fieldByName('fieldvalue');
			rs2.next();
		}
		var callbackmap = { 'function' : vtoffline.doSyncCallback, 'arguments' : {'gearid':gearid } };
		vtws.doCreate(module, valuesmap,  callbackmap);
		rs.next();
	} else {
		vtoffline.syncing = false;
		vtoffline.hideProgressBar();		
	}
}
vtoffline.doSyncCallback = function(result, args) {
	if(result) {
		var gearid = args.gearid;
		vtgears.executeSQL("DELETE FROM vtoffline_crmrecord WHERE gearid = " + gearid);
		vtgears.executeSQL("DELETE FROM vtoffline_crmentity WHERE gearid = " + gearid);
		vtoffline.showOfflineRecCount();
	}
	vtoffline.syncing = false;
	vtoffline.hideProgressBar();	
}
vtoffline.showOfflineRecCount = function() {
	var node = document.getElementById('vtoffline-records');
	if(node) {
		var rs = vtgears.executeSQL("SELECT COUNT(*) FROM vtoffline_crmentity WHERE status='offline'");
		if(rs.isValidRow()) node.innerHTML = app_strings.LBL_RECORDS + ' ' + rs.field(0);
	}
}	

vtoffline.redirect = function($url) {
	vtoffline.showProgressBar();
	location.href = $url;
}
vtoffline.showProgressBar = function(message) {
	if(message == null) message = 'Loading...';
	var pbar = document.getElementById('progress-bar');
	if(pbar) pbar.style.display = 'inline';
	if(pbar) pbar.innerHTML = message;
}
vtoffline.hideProgressBar = function() {
	var pbar = document.getElementById('progress-bar');
	if(pbar) pbar.style.display = 'none';
}

// Login Page Handlers
vtoffline.LoginPage = {
	Init : function() {
		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		$('#body').setTemplateURL(vtoffline.getTemplateURL('Login.jstpl'));
		for(key in toTemplate) $('#body').setParam(key, toTemplate[key]);
		$('#body').processTemplate();
	},
	doLogin : function(form) {
		if(!vtoffline.isAlive && vtoffline.checkLogin()) {
			vtoffline.LoginPage.doLoginCallback(true);
			return;
		}

		var username = form.username.value;
		var accesskey = form.accesskey.value;
		var url = form.serviceURL.value;

		vtws = new Vtiger_WSClient(url);
		vtws.doLogin(username, accesskey, vtoffline.LoginPage.doLoginCallback);	
	},
	doLoginCallback : function(flag) {
		if(flag) {
			vtoffline.IndexPage.Init();
		}
	}
}

// Index Page Handlers
vtoffline.IndexPage = {
	Init : function() {
		vtoffline.showProgressBar('Setting up...');
		vtgears.initialize('vtoffline', 'manifest.php', 'vtoffline');
		vtgears.initSchema(vtoffline.dbschema);
		vtoffline.hideProgressBar();

		if(!vtoffline.checkLogin()) {
			vtoffline.LoginPage.Init();
		} else {
			if(!vtoffline.modules) {
				vtoffline.showProgressBar('Loading...');
				vtws.doListTypes(vtoffline.IndexPage.Init2);
			}
			else vtoffline.IndexPage.Init2(null);
		}
	},
	Init2 : function(modules, args) {
		if(modules != null) vtoffline.modules = modules;
		else modules = vtoffline.modules;

		vtoffline.IndexPage.InitDescribe();

		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		toTemplate['MODULES'] = modules;
		toTemplate['USERNAME'] = vtws._serviceuser;

		$('#body').setTemplateURL(vtoffline.getTemplateURL('Home.jstpl'));
		for(key in toTemplate) $('#body').setParam(key, toTemplate[key]);
		$('#body').processTemplate();

		vtoffline.showOfflineRecCount();
		vtoffline.SetupTimers();
		vtoffline.hideProgressBar();
	},
	InitDescribe : function() {
		if(!vtoffline.describe) {
			vtoffline.showProgressBar('Describe...');
			var rs = vtgears.executeSQL('SELECT module, content FROM vtoffline_describe');
			while(rs.isValidRow()) {
				if(!vtoffline.describe) vtoffline.describe = {};
				vtoffline.describe[rs.fieldByName('module')] = vtws.toJSON(rs.fieldByName('content'));
				rs.next();
			}
			vtoffline.hideProgressBar();
		}
	},
	Logout : function() {
		if(vtoffline.isAlive) { vtoffline.redirect('index.php'); } 
		else {
			vtoffline.IndexPage.Cleanup();
			vtoffline.LoginPage.Init();
		}
	},
	Cleanup : function() {
		vtoffline.cancelPingServer();
		vtoffline.cancelSync();
	},

	OfflineSetup : function(moduleindex) {
		var modules = vtoffline.modules;
		if(moduleindex == null) moduleindex = 0;

		var module = modules[moduleindex];
	
		vtoffline.showProgressBar('Setting ' + module + '...');
		vtoffline.cancelPingServer(); // Cancel alive check now
		$('#vtoffline-setup').addClass('disabled');
	
		var callbackmap = { 'function' : vtoffline.IndexPage.OfflineSetupCallback, 'arguments' : {'moduleindex':moduleindex}};
		var describe = vtws.doDescribe(module, callbackmap);
	},
	OfflineSetupCallback : function(result, args) {
		if(result) {
			var modules = vtoffline.modules;
			var moduleindex = args.moduleindex;
			var module = modules[moduleindex];

			vtoffline.IndexPage.SaveDescribe(module, result);
			
			if(!vtoffline.describe) vtoffline.describe = {};
			vtoffline.describe[module] = result;

			vtoffline.hideProgressBar();
			// Call setup for next module.
			if(moduleindex < (modules.length - 1)) {
				vtoffline.IndexPage.OfflineSetup(moduleindex+1);
			}
			else {
				// At last enable the link and setup alive check
				$('#vtoffline-setup').removeClass('disabled');
				vtoffline.pingServer();
			}
		}
   	},
	
	SaveDescribe : function(module, describe) {
		var rs = vtgears.executeSQL('SELECT describeid FROM vtoffline_describe WHERE module=?', [module]);
		var query = '';
		if(rs.isValidRow()) {
			var id = rs.fieldByName('describeid');
			query = 'UPDATE vtoffline_describe SET content=? WHERE module=?';
		} else {
			query = 'INSERT INTO vtoffline_describe (content, module) VALUES (?,?)';
		}
		vtgears.executeSQL(query, [vtws.toJSONString(describe), module]);
	},

	Save : function(form) {
		var module = form.module.value;

		vtoffline.showProgressBar('Saving...');
		var rs = vtgears.executeSQL(
				"INSERT INTO vtoffline_crmentity (module, status) VALUES (?,?)",
				[module, 'offline']);
		var gearid = vtgears.lastInsertId();

		var formmap = vtoffline.formToMap(form);

		var query = "INSERT INTO vtoffline_crmrecord (gearid, fieldname, fieldvalue) VALUES (?,?,?)";
		for(formkey in formmap) {
			var formval = formmap[formkey];
			var rs2 = vtgears.executeSQL(query, [gearid, formkey, formval]);
		}

		vtoffline.hideProgressBar();
		vtoffline.showOfflineRecCount();
		vtoffline.doSync();
		vtoffline.IndexPage.Moduleview(module);
	},
	Syncview : function() {
		var module = 'Leads';
		var rs = vtgears.executeSQL('SELECT vtoffline_crmentity.gearid, module, fieldname, fieldvalue ' +
				' FROM vtoffline_crmrecord, vtoffline_crmentity WHERE vtoffline_crmentity.gearid= vtoffline_crmrecord.gearid');

		var columns = { 'gearid' : 'GearId', 'module' : 'Module', 'fieldname' : 'FieldName', 'fieldvalue' : 'FieldValue' };
		var records = [];
		while(rs.isValidRow()) {
			var record = {};
			var colindex = 0;
			for(colkey in columns) {
				record[colkey] = rs.field(colindex);
				++colindex;
			}
			records.push(record);
			rs.next();
		}
		
		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		toTemplate['MODULES'] = vtoffline.modules;
		toTemplate['MODULE'] = module;
		toTemplate['COLUMNS']= columns;
		toTemplate['RECORDS']= records;

		$('#workarea').setTemplateURL(vtoffline.getTemplateURL('SyncView.jstpl'));		
		for(key in toTemplate) $('#workarea').setParam(key, toTemplate[key]);
		$('#workarea').processTemplate();

		vtoffline.doSync();
	},

	Moduleview: function(module) {
		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		toTemplate['MODULES'] = vtoffline.modules;
		toTemplate['MODULE'] = module;
		$('#workarea').setTemplateURL(vtoffline.getTemplateURL('ModuleView.jstpl'));
		for(key in toTemplate) $('#workarea').setParam(key, toTemplate[key]);
		$('#workarea').processTemplate();
	},

	Columnsview : function(module) {
		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		toTemplate['MODULES'] = vtoffline.modules;
		toTemplate['MODULE'] = module;

		var usefields = [];
		if(vtoffline.describe) {
			var describe = vtoffline.describe[module];
			usefields = describe.fields;
		}
		toTemplate['FIELDS'] = usefields;

		$('#workarea').setTemplateURL(vtoffline.getTemplateURL('ColumnsView.jstpl'));
		for(key in toTemplate) $('#workarea').setParam(key, toTemplate[key]);
		$('#workarea').processTemplate();
	},

	ColumnsSave : function(form) {
		var formmap = vtoffline.formToMap(form);
		var module = form.module.value;
		var describe = vtoffline.describe[module];
		var fields  = describe.fields;
		var ismodified = false;
		for(var fidx = 0; fidx < fields.length; ++fidx) {
			var field = fields[fidx];
			var old_selected = field.selected;

			if(formmap[field.name]) field.selected = true;
			else field.selected = false;

			// Track change in state.
			if(field.selected != old_selected) { ismodified = true; }
		}
		if(ismodified) vtoffline.IndexPage.SaveDescribe(module, describe);
		vtoffline.IndexPage.Moduleview(module);
	},
	
	Createview : function(module) {
		var toTemplate = {};
		toTemplate['APP'] = app_strings;
		toTemplate['MODULES'] = vtoffline.modules;
		toTemplate['MODULE'] = module;
		toTemplate['USERNAME'] = vtws._serviceuser;

		var usefields = [];
		if(vtoffline.describe) {
			var describe = vtoffline.describe[module];
			var fields = describe.fields;
			for(var fidx = 0; fidx < fields.length; ++fidx) {
				var field = fields[fidx];
				if(field.selected || field.mandatory) usefields.push(field);
			}
		}
		toTemplate['FIELDS'] = usefields;

		$('#workarea').setTemplateURL(vtoffline.getTemplateURL('CreateView.jstpl'));
		for(key in toTemplate) $('#workarea').setParam(key, toTemplate[key]);
		$('#workarea').processTemplate();
	}
		   
}
