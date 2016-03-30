 // 全校与全院两个切换按钮效果
 var btn1=document.getElementById("btn1");
 var btn2=document.getElementById("btn2");
  function clickBtn1(){
  	btn1.style.background="#43ce80";
  	btn1.style.color="#FFFFFF";
  	btn2.style.background="#ffffff";
  	btn2.style.color="#43ce80";
    $("#school").show();
    $("#department").hide();
  }
  function clickBtn2(){
  	btn2.style.background="#43ce80";
  	btn2.style.color="#FFFFFF";
  	btn1.style.background="#ffffff";
  	btn1.style.color="#43ce80";
    $("#department").show();
    $("#school").hide();
  }

//