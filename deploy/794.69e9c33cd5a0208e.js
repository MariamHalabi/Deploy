"use strict";(self.webpackChunkTP05=self.webpackChunkTP05||[]).push([[794],{2794:(h,p,o)=>{o.r(p),o.d(p,{PanierModule:()=>g});var a=o(6895),c=o(9496),d=o(5873),n=o(8274),l=o(5519);function m(t,i){1&t&&(n.TgZ(0,"div")(1,"p"),n._uU(2,"Le panier est vide"),n.qZA()())}function u(t,i){if(1&t){const e=n.EpF();n.TgZ(0,"li",4)(1,"div",5)(2,"div",6)(3,"div",7),n._UZ(4,"img",8),n.qZA(),n.TgZ(5,"div",9)(6,"h4",10),n._uU(7),n.qZA(),n.TgZ(8,"p"),n._uU(9),n.qZA(),n.TgZ(10,"p"),n._uU(11),n.qZA(),n.TgZ(12,"button",11),n.NdJ("click",function(){const f=n.CHM(e).index,Z=n.oxw();return n.KtG(Z.removeProduitFromPanier(f))}),n._UZ(13,"span",12),n._uU(14," Supprimer du panier "),n.qZA()()()()()}if(2&t){const e=i.$implicit;n.xp6(4),n.s9C("src",e.image,n.LSH),n.xp6(3),n.Oqu(e.name),n.xp6(2),n.Oqu(e.description),n.xp6(2),n.hij("Prix : ",e.price," \u20ac")}}const C=[{path:"",component:(()=>{class t{constructor(e){this.store=e,this.products$=this.store.select(r=>r.panier.produits)}removeProduitFromPanier(e){this.store.dispatch(new d.S(e))}}return t.\u0275fac=function(e){return new(e||t)(n.Y36(l.yh))},t.\u0275cmp=n.Xpm({type:t,selectors:[["app-composant-panier"]],decls:8,vars:6,consts:[[1,"container"],[4,"ngIf"],[1,"media-list"],["class","media",4,"ngFor","ngForOf"],[1,"media"],[1,"panel","panel-default"],[1,"panel-body"],[1,"media-left"],["alt","...",1,"media-object",3,"src"],[1,"media-body"],[1,"media-heading"],["type","button",1,"btn","btn-danger",3,"click"],["aria-hidden","true",1,"glyphicon","glyphicon-remove"]],template:function(e,r){if(1&e&&(n.TgZ(0,"div",0)(1,"h2"),n._uU(2,"Votre panier"),n.qZA(),n.YNc(3,m,3,0,"div",1),n.ALo(4,"async"),n.TgZ(5,"ul",2),n.YNc(6,u,15,4,"li",3),n.ALo(7,"async"),n.qZA()()),2&e){let s;n.xp6(3),n.Q6J("ngIf",0==(null==(s=n.lcZ(4,2,r.products$))?null:s.length)),n.xp6(3),n.Q6J("ngForOf",n.lcZ(7,4,r.products$))}},dependencies:[a.sg,a.O5,a.Ov]}),t})()}];let g=(()=>{class t{}return t.\u0275fac=function(e){return new(e||t)},t.\u0275mod=n.oAB({type:t}),t.\u0275inj=n.cJS({imports:[a.ez,c.Bz.forChild(C)]}),t})()}}]);