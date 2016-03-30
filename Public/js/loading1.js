//color f63a0f  f27011 f2b01e f2d31b 86e01e
var loadingAnim={
	bgColor:"rgba(0,0,0,0.4)",
	bfColor:"#f2b01e",
	zIndex:10,
	init:function(){
		var htmlNode=document.getElementsByTagName("html")[0];
		// htmlNode.style.width="100%";
		// htmlNode.style.height="100%";
		var bodyNode=document.getElementsByTagName("body")[0];
		// bodyNode.style.width="100%";
		// bodyNode.style.height="100%";
		// bodyNode.style.margin="0";
		// bodyNode.style.padding="0";
		var containerNode=document.createElement("div");
		containerNode.classList.add("loading_container");
		containerNode.style.zIndex=this.zIndex;
		containerNode.style.height="100%";
		containerNode.style.width="100%";
		containerNode.style.backgroundColor=this.bgColor;
		var spinnerNode=document.createElement("div");
		spinnerNode.classList.add("spinner");
		spinnerNode.style.top="45%";
		var cube1Node=document.createElement("div");
		cube1Node.classList.add("cube1");
		cube1Node.style.backgroundColor=this.bfColor;
		var cube2Node=document.createElement("div");
		cube2Node.classList.add("cube2");
		cube2Node.style.backgroundColor=this.bfColor;
		spinnerNode.appendChild(cube1Node);
		spinnerNode.appendChild(cube2Node);
		containerNode.appendChild(spinnerNode);
		bodyNode.appendChild(containerNode);
	},
	show:function(){
		var containerObj=document.getElementsByClassName('loading_container')[0];
		if (containerObj) {
			containerObj.style.display="block";
		}else{
			this.init();
		}
	},
	hide:function(){
		var containerObj=document.getElementsByClassName('loading_container')[0];
		containerObj.style.display="none";
	}
}