"use strict";window.bubbly=function(t){function o(){return Math.random()}var e=t||{},r=e.canvas||document.createElement("canvas"),n=r.width,a=r.height;null===r.parentNode&&(r.setAttribute("style","position:fixed;z-index:-1;left:0;top:0;min-width:100vw;min-height:100vh;"),n=r.width=window.innerWidth,a=r.height=window.innerHeight,document.body.appendChild(r));var i=r.getContext("2d");i.shadowColor=e.shadowColor||"#fff",i.shadowBlur=e.blur||4;var l=i.createLinearGradient(0,0,n,a);l.addColorStop(0,e.colorStart||"#2AE"),l.addColorStop(1,e.colorStop||"#17B");for(var h=e.bubbles||Math.floor(.02*(n+a)),d=[],c=0;c<h;c++)d.push({f:(e.bubbleFunc||function(){return"hsla(0, 0%, 100%, "+.1*o()+")"}).call(),x:o()*n,y:o()*a,r:4+o()*n/25,a:o()*Math.PI*2,v:.1+.5*o()});!function t(){if(null===r.parentNode)return cancelAnimationFrame(t);!1!==e.animate&&requestAnimationFrame(t),i.globalCompositeOperation="source-over",i.fillStyle=l,i.fillRect(0,0,n,a),i.globalCompositeOperation=e.compose||"lighter",d.forEach(function(t){i.beginPath(),i.arc(t.x,t.y,t.r,0,2*Math.PI),i.fillStyle=t.f,i.fill(),t.x+=Math.cos(t.a)*t.v,t.y+=Math.sin(t.a)*t.v,t.x-t.r>n&&(t.x=-t.r),t.x+t.r<0&&(t.x=n+t.r),t.y-t.r>a&&(t.y=-t.r),t.y+t.r<0&&(t.y=a+t.r)})}()};