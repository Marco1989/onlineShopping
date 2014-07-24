var ssnflag = false;
var phoneflag = false; 
var emailflag = false;

function checkObj(obj,type) { //check normal text fields
    if (isEmpty(obj.value) == 1){
        return false;
    }
    setmark(type,"good"); 
}

function showElements(){  //final check on submit for data integrity and hash
    var allele = document.getElementsByTagName("input");
    var values = "";
    var itype = new Array("Salutation","First Name","Last Name","Username","Password","Confirmed","SSN","Telephone","Email");
    for (var i = 0; i < allele.length; i++){
        if(allele[i].name.match('p_'))  //cycle all input elements that match p_
        {
            if (isEmpty(allele[i].value) == 1){
               alert(itype[i] + " is incomplete, please fix and try again!");
               return false;
            }
            if (allele[i].name == "p_ssnumber" && ssnflag == false){
               alert("SSN needs corrected.");
               allele[i].value = "";
               return false;
            }
            if (allele[i].name == "p_telephone" && phoneflag == false){
               alert("Telephone needs to be 10 numbers.");
               allele[i].value = "";
               return false;
            }
            if (allele[i].name == "p_email" && emailflag == false){
                alert("Issue with email, please correct.");
                allele[i].value = "";
                return false;
            }
            values = values.concat(allele[i].value);  //makes long variable of values to hash
        }
    }  
    var digest = hex_md5(values);  //hash it
    document.getElementById("digest").value=digest;  //set hidden variable
    return true; 
}

function checkPw(obj) {  //visual to help users since password field is asterix
    pw1 = obj.form.p_password.value;
    pw2 = obj.value;
    if (isEmpty(pw2)  == 1){
       return;
    }
    var bgcolor = "pink";  //color for bad password
    if (pw1 != pw2) {
        obj.style.backgroundColor = bgcolor;
        setmark("password2","bad");
    }else{
        obj.style.backgroundColor = "white";
        setmark("password2","good");
    } 
}

function checkData(form){  // going to submit our data to wherever
    if (checkPw2(form) == false){
        return false;
    }
    if (showElements() == false){
        return false;
    }
    alert("Good Data, sending hash: " + document.getElementById("digest").value);  //for now just show hash remove for prod
    return true;
}

function checkPw2(form) {  //does verification on submit of passwords
    pw1 = form.p_password.value;
    pw2 = form.p_password2.value;
    if (pw1 != pw2) {
        alert ("\nYou did not enter the same new password twice. Please re-enter your password.")
        return false;
    }
    else return true;
}

function filter(obj)  
{
    switch(obj.name)  //filter bad data on input type to help users
    {
        case "p_telephone":
            if(obj.value.match(/[^\d\-\(\)]/g,''))  //telephones should be digits, dashes, parens
            {
                obj.value = obj.value.replace(/[^\d\-\(\)]/g,'');
            }
            break;
        case "p_ssnumber":
            if(obj.value.match(/[^\d\-]/g,''))  //should only be digits and/or dash
            {
                obj.value = obj.value.replace(/[^\d\-]/g,'');
            }
            break;
	case 'p_fname':
        case 'p_lname':
            if(obj.value.match(/[^A-Za-z\-\' ]/g,''))  //names letters, dashes, apostrophe, spaces
            {
		obj.value = obj.value.replace(/[^A-Za-z\-\' ]/g,'');
            }
            break;
	default:
            if(obj.value.match(/\W/g,''))  //anything else we may filter
            {
                obj.value = obj.value.replace(/\W/g,'');
            }
            break;
	}
}

 function validEmail(obj){  //strict email checking
    if (isEmpty(obj.value) == 1){
        return false;
    }
    invalidChars = " ()<>,;:\"[];%#$&^\'"
    email = obj.value;
    for (i = 0; i < invalidChars.length; i++) { // does it contain any invalid characters?
        badChar = invalidChars.charAt(i)
        if (email.indexOf(badChar, 0) > -1) {
            setmark("email","bad");
            return false;
        }
    }
    atPos = email.indexOf("@", 1) // there must be one "@" symbol
    if (atPos == -1) {
        setmark("email","bad");
        return false;
    }
    if (email.indexOf("@", atPos + 1) != -1) { // and only one "@" symbol
        setmark("email","bad");
        return false;
    }
    periodPos = email.indexOf(".", atPos)
    if (periodPos == -1) { // and at least one "." after the "@"
        setmark("email","bad");
        return false;
    }
    if (periodPos + 3 > email.length) { // must be at least 2 characters after the "."
        setmark("email","bad");
        return false;
    }
    setmark("email","good");
    emailflag = true;
    return true;
}

function checkNum(obj, name){  //check important numbers
    if (isEmpty(obj.value) == 1){
        return false;
    }
    var fullnum = obj.value;
    var re;
    if (name == "ssnumber"){
        re = new RegExp(/^(\d{3})\s?-?(\d{2})\s?-?(\d{4})$/);  //regex to verify ssn
        if (re.test(fullnum)){
            ssnflag = true;
            obj.value = RegExp.$1 + "-" + RegExp.$2 + "-" + RegExp.$3;  //make it a known format
            setmark(name,"good");
        }else{
            setmark(name,"bad");
            return false;
        }
    }
    if (name == "telephone"){
        re = new RegExp(/^\(?(\d{3})\)?\s?-?(\d{3})\s?-?(\d{4})$/);  //regex to verify phone
        if (re.test(fullnum)){
            phoneflag = true;
            obj.value = "(" + RegExp.$1 + ")" + " " + RegExp.$2 + "-" + RegExp.$3;  //make it a known format
            setmark(name,"good");
        }else{
            setmark(name,"bad");
            return false;
        }
    }
    return true;    
}

function setmark(name,type){  //set our visual marks
    if (type=="bad"){
        document.getElementById(name).innerHTML ="<font color='red'>&#x2717;</font>";  //red x
    }else{
        document.getElementById(name).innerHTML="&#x2713;"  //green checkmark
    }
    return true;
}

function isEmpty(mychar){
    answer = 0;  //graceful check with empty fields
    count = 0;
    for (var i = 0; i < mychar.length; i++) {
        if (mychar.charAt(i) != " ") {
            count = count + 1;
        }
    }
    if (count == 0) {
        answer = 1;
    }
        return answer;
}
