//어제날짜 <yesterday>
var nowDate = new Date();
var yesterDate = nowDate.getTime() - (1 * 24 * 60 * 60 * 1000);
nowDate.setTime(yesterDate);

var yesterYear = nowDate.getFullYear();
var yesterMonth = nowDate.getMonth() + 1;
var yesterDay = nowDate.getDate();

if(yesterMonth < 10){ yesterMonth = "0" + yesterMonth; }
if(yesterDay < 10) { yesterDay = "0" + yesterDay; }

var yesterday = yesterYear + "-" + yesterMonth + "-" + yesterDay;

// 최근 일주일 <어제날짜 기준> <lastweek>
var nowDate = new Date();
var weekDate = nowDate.getTime() - (7 * 24 * 60 * 60 * 1000);
nowDate.setTime(weekDate);

var weekYear = nowDate.getFullYear();
var weekMonth = nowDate.getMonth() + 1;
var weekDay = nowDate.getDate();

if(weekMonth < 10){ weekMonth = "0" + weekMonth; }
if(weekDay < 10) { weekDay = "0" + weekDay; }

var lastweek = weekYear + "-" + weekMonth + "-" + weekDay;

//최근 30일 <어제날짜 기준> <month>
var nowDate = new Date();
var monthDate = nowDate.getTime() - (30 * 24 * 60 * 60 * 1000);
nowDate.setTime(monthDate);

var monthYear = nowDate.getFullYear();
var monthMonth = nowDate.getMonth() + 1;
var monthDay = nowDate.getDate();

if(monthMonth < 10){ monthMonth = "0" + monthMonth; }
if(monthDay < 10) { monthDay = "0" + monthDay; }

var month = monthYear + "-" + monthMonth + "-" + monthDay;

//이번달 1일 <this_firstday>
var date = new Date();
var a = date.getDate()-1;
var this_first = date.getTime() - (a * 24 * 60 * 60 * 1000);
date.setTime(this_first);

var this_month = date.getMonth()+1;
var this_day = date.getDate();
if(this_month < 10){this_month = "0" +this_month;}
if(this_day < 10){this_day = "0" +this_day;}

var this_firstday = date.getFullYear()+'-'+this_month+'-'+this_day;

//저번달 말일 <last_lastday>
var last_last = date.getTime() - (1 * 24 * 60 * 60 * 1000);
date.setTime(last_last);

var last_l_year = date.getFullYear();
var last_l_month = date.getMonth()+1;
var last_l_day = date.getDate();
if(last_l_month < 10){last_l_month = "0" +last_l_month;}
if(last_l_day < 10){last_l_day = "0" +last_l_day;}

var last_lastday = date.getFullYear()+'-'+last_l_month+'-'+last_l_day;

//저번달 1일 <last_firstday>
var b = date.getDate()-1;
var last_first = date.getTime() - (b * 24 * 60 * 60 * 1000);
date.setTime(last_first);

var last_f_month = date.getMonth()+1;
var last_f_day = date.getDate();
if(last_f_month < 10){last_f_month = "0" +last_f_month;}
if(last_f_day < 10){last_f_day = "0" +last_f_day;}

var last_firstday = date.getFullYear()+'-'+last_f_month+'-'+last_f_day;
