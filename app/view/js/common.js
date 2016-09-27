var PAGE_BASE_URL = "../../AppUserAuth/";
var RESULT_URL = "../../AppUserAuth/result.html";
var API_BASE_URL = "../../userauth-webapi/api/";

//設定情報取得
function getConfig(){

    var url = API_BASE_URL + "authConfig";
    var config = {
                   tmpAuthWaitMinutes : 5,
                   tmpAuthFailLimitCount : 0,
                   authKeepMinutes : 120,
                   periodHours : 72,
                   periodLimitCount : 20,
                   noAuthDays : 180
               };
    $.ajax({
        crossDomain: true,
        type : 'get',
        url : url,
        async:false,
        dataType: "json",
        cache: false,
    //成功時
    }).done(function(data, textStatus, xhr){
        config = {
                   tmpAuthWaitMinutes : data.tmpAuthWaitMinutes,
                   tmpAuthFailLimitCount : data.tmpAuthFailLimitCount,
                   authKeepMinutes : data.authKeepMinutes,
                   periodHours : data.periodHours,
                   periodLimitCount : data.periodLimitCount,
                   noAuthDays : data.noAuthDays
                 };
    }).fail(function(xhr, textStatus, errorThrown){
        console.log('getConfig Error! ' + textStatus + ' ' + errorThrown);
    });

    return config;
}

//Facebook情報取得
function getFbConfig(wlcKindId){

    var url = API_BASE_URL + "authConfig/fb/" + wlcKindId;
    var config = {
                   appId : '0',
                   version : 'v.2.6'
               };
    $.ajax({
        crossDomain: true,
        type : 'get',
        url : url,
        async:false,
        dataType: "json",
        cache: false,
    //成功時
    }).done(function(data, textStatus, xhr){
        config = {
                   appId : data.appId,
                   version : data.version
                 };
    }).fail(function(xhr, textStatus, errorThrown){
        console.log('getFbConfig Error! ' + textStatus + ' ' + errorThrown);
    });

    return config;
}

//Yahoo情報取得
function getYahooConfig(wlcKindId){

    var url = API_BASE_URL + "authConfig/yahoo/" + wlcKindId;
    var config = {
                   redirectUri : '',
                   clientId : '',
                   clientSecret : '',
                   state : '',
                   nonce : ''
               };
    $.ajax({
        crossDomain: true,
        type : 'get',
        url : url,
        async:false,
        dataType: "json",
        cache: false,
    //成功時
    }).done(function(data, textStatus, xhr){
        config = {
                   scope : data.scope,
                   redirectUri : data.redirectUri,
                   clientId : data.clientId,
                   clientSecret : data.clientSecret,
                   state : data.state,
                   nonce : data.nonce
                 };
    }).fail(function(xhr, textStatus, errorThrown){
        console.log('getYahooConfig Error! ' + textStatus + ' ' + errorThrown);
    });
    return config;
}

//証跡設定情報取得
function getTrailConfig(authKindId){

    var trailConfig = new Array();
    var url = API_BASE_URL + "authConfig/trail/" + authKindId;
    $.ajax({
        crossDomain: true,
        type : 'get',
        url : url,
        async:false,
        dataType: "json",
        cache: false,
    //成功時
    }).done(function(data, textStatus, xhr){
        var i = 0;
        var j = 0;
        $(data).each( function() {
            if(data[i].enableFlg){
                trailConfig[j] = data[i].key;
                j++;
            }
            i++;
        });
    }).fail(function(xhr, textStatus, errorThrown){
        console.log('getConfig Error! ' + textStatus + ' ' + errorThrown);
    });

    return trailConfig;
}

//仮認証処理
function entryAuthInfo(mailAddress, snsTrailJson, snsNoLoginFlg) {
//alert("WifiAuth_flg="+$.cookie("WifiAuth_flg"));
//alert("cookie.snsNoLoginFlg="+$.cookie("snsNoLoginFlg"));
//alert("snsNoLoginFlg="+snsNoLoginFlg);
//alert("localStorage.currentLocaleId="+localStorage.currentLocaleId);
//alert("mailAddress="+mailAddress);
    if(mailAddress == undefined || mailAddress == "" || mailAddress == null) {
        mailAddress = $.cookie("mail_address");
    }
    if(snsNoLoginFlg == undefined || snsNoLoginFlg == "" || snsNoLoginFlg == null) {
        snsNoLoginFlg = $.cookie("snsNoLoginFlg");
    }

    //設定情報（仮認証タイムアウト分、再認証不要日数）を取得
    var config = getConfig();
    var tmpAuthWaitMinutes = config.tmpAuthWaitMinutes;
    var noAuthDays = config.noAuthDays;
    exDate = new Date();
    exDate.setTime( exDate.getTime() + ( tmpAuthWaitMinutes * 60 * 1000 ));
    var firstFlg = false;
    if($.cookie("first_flg") == undefined){
        //$.cookie("first_flg", true, { expires: exDate , path: "/" });
        $.cookie("first_flg", true, { path: "/" });
    }else{
        //$.cookie("first_flg", false, { expires: exDate , path: "/" });
        $.cookie("first_flg", false, { path: "/" });
    }
    var wlcKindId = 1;
    if(document.location.href.toLowerCase().indexOf("aruba") > 0){
        wlcKindId = 2;
    }
    var languageId = localStorage.currentLocaleId;
    //画面情報をJSONにセット
    if(snsTrailJson != undefined){
	    var JSONdata = {
	        "wlcKindId": wlcKindId,
	        "languageId": languageId,
	        "authKindId": $.cookie("auth_kind_id"),
	        "mailAddress": mailAddress,
	        "username": $.cookie("username"),
	        "password": $.cookie("password"),
	        "firstFlg": $.cookie("first_flg"),
	        "snsNoLoginFlg": snsNoLoginFlg,
	        "snsTrailInfos" : JSON.parse(snsTrailJson),
	        "answerDetails" : JSON.parse(decodeURI($.cookie("answerDetails")))
	    };
	}else{
	    var JSONdata = {
	        "wlcKindId": wlcKindId,
	        "languageId": languageId,
	        "authKindId": $.cookie("auth_kind_id"),
	        "mailAddress": mailAddress,
	        "username": $.cookie("username"),
	        "password": $.cookie("password"),
	        "firstFlg": $.cookie("first_flg"),
	        "snsNoLoginFlg": snsNoLoginFlg,
	        "answerDetails" : JSON.parse(decodeURI($.cookie("answerDetails")))
	    };
	}

    //alert(JSON.stringify(JSONdata));
    // console.log(JSON.stringify(JSONdata));
    var url = API_BASE_URL + "auth/entry";

    if($.cookie("auth_kind_id") == 1){
        authCateId = 1;
    }else{
        authCateId = 2;
    }
    var rtnCode = 9;
    //仮認証処理実行
    //$("#main").html("<img src='./js/ajaxloader.gif'/>");
    $.ajax({
        crossDomain: true,
        type : 'post',
        url : url,
        async:false,
        data : JSON.stringify(JSONdata),
        timeout:10000,
        contentType: 'application/JSON',
        scriptCharset: 'utf-8',
    //成功時
    }).done(function(data, textStatus, xhr){
        //alert('data.returnCode:' + data.returnCode);
        //$("#main").empty();
        $.removeCookie("username");
        $.removeCookie("password");
        if(data.returnCode != undefined){
            // console.log("data.returnCode:" + data.returnCode);
            //if(data.returnCode != 0){
            //    $.removeCookie("WifiAuth_flg");
            //    $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
            //}
            switch(data.returnCode){
            case 0:
                $.removeCookie("answerDetails");
                //$.removeCookie("first_flg", { path: "/" });
                $.cookie("WifiAuth_flg", 2, { expires: exDate });
                if(authCateId == 1){
                    $.removeCookie("WifiAuth_flg");
                    $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
                    if(data.successKind == 1){
                        $.cookie("authKey", data.authKey, { expires: exDate , path: "/" });
                        $.cookie("hm", data.hm, { expires: exDate , path: "/" });
                        location.href = RESULT_URL + "?q=0&r=1" + "&l=" + languageId;
                    }else if(data.successKind == 2){
                        $.removeCookie("mail_address", { path: "/" ,domain: "sdn.auth"});
                        $.cookie("NoAnswerFlg", 1, { expires: noAuthDays });
	                    // console.log("data.redirectUrl:" + data.redirectUrl);
	                    location.href = RESULT_URL + "?q=0&r=5" + "&l=" + languageId;
                    }
                }else if(authCateId == 2){
                    // console.log("data.authKey:" + data.authKey);
                    $.cookie("authKey", data.authKey, { expires: 1 , path: "/" });
                    if($.cookie("snsNoLoginFlg") == undefined){
                        if(data.successKind == 4){
	                        $.removeCookie("snsNoLoginFlg", { path: "/" });
	                        $.removeCookie("WifiAuth_flg");
	                        $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
	                        $.removeCookie("mail_address", { path: "/" ,domain: "sdn.auth"});
                            $.cookie("NoAnswerFlg", 1, { expires: noAuthDays });
                            // console.log("data.redirectUrl:" + data.redirectUrl);
                            location.href = data.redirectUrl;
//                        }else{
//                            completeFbAuth();
                        }
                    }
                    //if($.cookie("auth_kind_id") == 3){
                        $.removeCookie("WifiAuth_flg");
                    //}
                }
                rtnCode = 0;
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 9:
                $.removeCookie("snsNoLoginFlg", { path: "/" });
                $.removeCookie("answerDetails");
                //$.removeCookie("first_flg", { path: "/" });
                $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
                $.removeCookie("mail_address", { path: "/" ,domain: "sdn.auth"});
                location.href = RESULT_URL + "?q=" + data.returnCode + "&l=" + languageId;
                break;
            case 10:
                //Arubaで認証できなかった場合
                $.cookie("WifiAuth_flg", 3, { expires: exDate });
                rtnCode = 10;
                break;
            default:
                location.href = RESULT_URL + "?q=9" + "&l=" + languageId;
                rtnCode = 9;
                break;
            }
        }
    }).fail(function(xhr, textStatus, errorThrown){
        alert('entryAuthInfo Error! ' + $.parseJSON(xhr.responseText) + textStatus + ' ' + errorThrown);
        $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
        $.removeCookie("mail_address", { path: "/" ,domain: "sdn.auth"});
        $.removeCookie("username");
        $.removeCookie("password");
        //$.removeCookie("first_flg", { path: "/" });
        $.removeCookie("answerDetails");
        $.removeCookie("WifiAuth_flg");
        location.href = RESULT_URL + "?q=9" + "&l=" + languageId;
        rtnCode = 9;
    }).always(function(xhr, textStatus){
        //$.removeCookie("WifiAuth_flg");
    });

    return rtnCode;

}

//本認証処理
function completeSnsAuth(mailAddress, snsTrailJson) {
//alert("snsTrailJson="+snsTrailJson);
    if(snsTrailJson == undefined){
        alert("SNS証跡情報が取得できなかったため、処理を中断します。");
        $.removeCookie("WifiAuth_flg");
        $.removeCookie("snsNoLoginFlg", { path: "/" });
        $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
        return 9;
    }

    if($.cookie("authKey") == undefined){
        alert("認証キーが設定されていませんので処理を中断します。仮認証処理が正常に完了しておりません。");
        $.removeCookie("WifiAuth_flg");
        $.removeCookie("snsNoLoginFlg", { path: "/" });
        $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
        return 9;
    }
    authKey = $.cookie("authKey");
    //設定情報（仮認証タイムアウト分、再認証不要日数）を取得
    var config = getConfig();
    var tmpAuthWaitMinutes = config.tmpAuthWaitMinutes;
    var noAuthDays = config.noAuthDays;
    exDate = new Date();
    exDate.setTime( exDate.getTime() + ( tmpAuthWaitMinutes * 60 * 1000 ));
    if($.cookie("auth_kind_id") == 3){
        $.cookie("snsNoLoginFlg", true, { expires: exDate, path: "/" });
    }

    var wlcKindId = 1;
    if(document.location.href.toLowerCase().indexOf("aruba") > 0){
        wlcKindId = 2;
    }

    var JSONdata = {
        "wlcKindId": wlcKindId,
        "mailAddress": mailAddress,
        "authKindId": $.cookie("auth_kind_id"),
        "authKey": authKey,
        "snsNoLoginFlg": $.cookie("snsNoLoginFlg"),
        "snsTrailInfos" : JSON.parse(snsTrailJson)
    };
    // console.log(JSON.stringify(JSONdata));
    //alert(JSON.stringify(JSONdata));

    var url = API_BASE_URL + "auth/snsComplete";
    $.ajax({
        type : 'post',
        url : url,
        data : JSON.stringify(JSONdata),
        contentType: 'application/JSON',
        scriptCharset: 'utf-8',
    //成功時
    }).done(function(data, textStatus){
        $.removeCookie("WifiAuth_flg");
        $.removeCookie("snsNoLoginFlg", { path: "/" });
        if(data.returnCode != undefined){
            $.removeCookie("auth_kind_id", { path: "/" ,domain: "sdn.auth"});
            $.removeCookie("mail_address", { path: "/" ,domain: "sdn.auth"});
            $.removeCookie("authKey", { path: "/" });
            switch(data.returnCode){
            case 0:
                $.cookie("NoAnswerFlg", 1, { expires: noAuthDays , path: "/" });
                console.log("data.redirectUrl:" + data.redirectUrl);
                location.href = data.redirectUrl;
                break;
            case 2:
            case 3:
            case 6:
            case 7:
            case 9:
                location.href = RESULT_URL + "?q=" + data.returnCode;
                break;
            default:
                location.href = RESULT_URL + "?q=9";
                break;
            }
        }

    }).fail(function(xhr, textStatus, errorThrown){
        alert('completeSnsAuth Error! ' + textStatus + ' ' + errorThrown);
        $.removeCookie("WifiAuth_flg");
        $.removeCookie("snsNoLoginFlg", { path: "/" });
        $.removeCookie("auth_kind_id", { path: "/" });
        location.href = RESULT_URL + "?q=9";
    }).always(function(xhr, textStatus){
        $.removeCookie("WifiAuth_flg");
        $.removeCookie("snsNoLoginFlg", { path: "/" });
        $.removeCookie("auth_kind_id", { path: "/" });
    });

}
