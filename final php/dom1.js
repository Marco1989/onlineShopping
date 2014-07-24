function filter1(obj)  
{

            if(obj.value.match(/[^A-Za-z\-\' ]/g,''))  //names letters, dashes, apostrophe, spaces
            {
		obj.value = obj.value.replace(/[^A-Za-z\-\' ]/g,'');
            }
         
          
	
}
function filter2(obj)  
{
    
       
            if(obj.value.match(/[^\d\-\(\)]/g,''))  //telephones should be digits, dashes, parens
            {
                obj.value = obj.value.replace(/[^\d\-\(\)]/g,'');
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
function checkObj(obj,type) { //check normal text fields
    if (isEmpty(obj.value) == 1){
        return false;
    }
    setmark(type,"good"); 
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

function setmark(name,type){  //set our visual marks
    if (type=="bad"){
        document.getElementById(name).innerHTML ="<font color='white'>&#x2717;</font>";  //red x
    }else{
        document.getElementById(name).innerHTML="&#x2713;"  //green checkmark
    }
    return true;
}












