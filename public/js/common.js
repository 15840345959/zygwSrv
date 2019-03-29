// 接口部分
//基本的ajax访问后端接口类
function ajaxRequest(url, param, method, callBack) {
    console.log("url:" + url + " method:" + method + " param:" + JSON.stringify(param));
    $.ajax({
        type: method,  //提交方式
        url: url,//路径
        data: param,//数据，这里使用的是Json格式进行传输
        contentType: "application/json", //必须有
        dataType: "json",
        success: function (ret) {//返回数据根据结果进行相应的处理
            console.log("ret:" + JSON.stringify(ret));
            callBack(ret)
        },
        error: function (err) {
            console.log(JSON.stringify(err));
            console.log("responseText:" + err.responseText);
            callBack(err)
        }
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////

//是否输出打印信息的开关，为true 时输出打印信息
var DEBUG = true;

var consoledebug = (DEBUG) ? console : new nodebug();

function nodebug() {
}

nodebug.prototype.log = function (str) {
}
nodebug.prototype.warn = function (str) {
}


/*
 * 校验手机号js
 *
 * By TerryQi
 */

function isPoneAvailable(phone_num) {
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(phone_num)) {
        return false;
    } else {
        return true;
    }
}

// 判断参数是否为空
function judgeIsNullStr(val) {
    if (val == null || val == "" || val == undefined || val == "未设置") {
        return true
    }
    return false
}

// 判断参数是否为空
function judgeIsAnyNullStr() {
    if (arguments.length > 0) {
        for (var i = 0; i < arguments.length; i++) {
            if (!isArray(arguments[i])) {
                if (arguments[i] == null || arguments[i] == "" || arguments[i] == undefined || arguments[i] == "未设置" || arguments[i] == "undefined") {
                    return true
                }
            }
        }
    }
    return false
}

// 判断数组时候为空, 服务于 judgeIsAnyNullStr 方法
function isArray(object) {
    return Object.prototype.toString.call(object) == '[object Array]';
}


// 文字转html，主要是进行换行转换
function Text2Html(str) {
    if (str == null) {
        return "";
    } else if (str.length == 0) {
        return "";
    }
    str = str.replace(/\r\n/g, "<br>")
    str = str.replace(/\n/g, "<br>");
    return str;
}

//null变为空str
function nullToEmptyStr(str) {
    if (judgeIsNullStr(str)) {
        str = "";
    }
    return str;
}


/*
 * 获取url中get的参数
 *
 * By TerryQi
 *
 * 2017-12-23
 *
 */
function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}


//判断密码的复杂度
function checkPass(s) {
    if (s.length < 8) {
        return 0;
    }
    var ls = 0;
    if (s.match(/([a-z])+/)) {
        ls++;
    }
    if (s.match(/([0-9])+/)) {
        ls++;
    }
    if (s.match(/([A-Z])+/)) {
        ls++;
    }
    if (s.match(/[^a-zA-Z0-9]+/)) {
        ls++;
    }
    return ls
}


/*
 * 去掉空格、回车、换行
 *
 * By TerryQi
 *
 * 2018-09-12
 */
function formateText(str_val) {
    var resultStr = str_val.replace(/\ +/g, ""); //去掉空格
    resultStr = resultStr.replace(/[ ]/g, "");    //去掉空格
    resultStr = resultStr.replace(/[\r\n]/g, ""); //去掉回车换行
    resultStr = resultStr.replace(/[\n]/g, ""); //去掉换行
    resultStr = resultStr.replace(/[\r]/g, ""); //去掉回车

    resultStr = resultStr.replace(/(^\s+)|(\s+$)/g, "");
    resultStr = resultStr.replace(/\s/g, "");

    return resultStr;
}