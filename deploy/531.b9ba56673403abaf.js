"use strict";(self.webpackChunkTP05=self.webpackChunkTP05||[]).push([[531],{9179:(b,p,e)=>{e.r(p),e.d(p,{CatalogueModule:()=>_});var a=e(6895),r=e(9496),l=e(5873),n=e(8274),C=e(2340),g=e(529);let s=(()=>{class t{constructor(o){this.http=o,this.env=C.N}getProduits(){return this.http.get(this.env.apiUrl+"/products")}}return t.\u0275fac=function(o){return new(o||t)(n.LFG(g.eN))},t.\u0275prov=n.Yz7({token:t,factory:t.\u0275fac,providedIn:"root"}),t})();var u=e(5519);const d=function(t){return["/catalogue/product/",t]};function P(t,c){if(1&t){const o=n.EpF();n.TgZ(0,"div",4)(1,"div",5)(2,"a",6),n._UZ(3,"img",7),n.qZA(),n.TgZ(4,"div",8)(5,"h6")(6,"a",6),n._uU(7),n.qZA()(),n.TgZ(8,"span",9),n._uU(9),n.qZA(),n._UZ(10,"br"),n.TgZ(11,"span",10),n._uU(12),n.qZA(),n.TgZ(13,"div")(14,"button",11),n.NdJ("click",function(){const f=n.CHM(o).$implicit,h=n.oxw();return n.KtG(h.addProduitToPanier(f))}),n._UZ(15,"span",12),n._uU(16," Ajouter au panier "),n.qZA()()()()()}if(2&t){const o=c.$implicit;n.xp6(2),n.Q6J("routerLink",n.VKq(6,d,o.id)),n.xp6(1),n.s9C("src",o.image,n.LSH),n.xp6(3),n.Q6J("routerLink",n.VKq(8,d,o.id)),n.xp6(1),n.Oqu(o.name),n.xp6(2),n.Oqu(o.description),n.xp6(3),n.hij(" ",o.price," \u20ac")}}let M=(()=>{class t{constructor(o,i){this.catalogueService=o,this.store=i}ngOnInit(){this.products$=this.catalogueService.getProduits()}addProduitToPanier(o){this.store.dispatch(new l.e(o))}OnReset(){}}return t.\u0275fac=function(o){return new(o||t)(n.Y36(s),n.Y36(u.yh))},t.\u0275cmp=n.Xpm({type:t,selectors:[["app-composant-produit"]],decls:6,vars:3,consts:[[1,"container","bootstrap","snipets"],[1,"text-center","text-muted"],[1,"row","flow-offset-1"],["class","col-xs-6 col-md-6",4,"ngFor","ngForOf"],[1,"col-xs-6","col-md-6"],[1,"product","tumbnail","thumbnail-3","product-bloc"],[3,"routerLink"],["alt","",1,"imgP",3,"src"],[1,"caption"],[2,"font-size","x-small"],[1,"price","sale"],["type","button",1,"btn","btn-default",2,"float","right",3,"click"],["aria-hidden","true",1,"glyphicon","glyphicon-shopping-cart"]],template:function(o,i){1&o&&(n.TgZ(0,"div",0)(1,"h1",1),n._uU(2,"Product catalog"),n.qZA(),n.TgZ(3,"div",2),n.YNc(4,P,17,10,"div",3),n.ALo(5,"async"),n.qZA()()),2&o&&(n.xp6(4),n.Q6J("ngForOf",n.lcZ(5,1,i.products$)))},dependencies:[a.sg,r.yS,a.Ov],styles:['body[_ngcontent-%COMP%]{margin-top:20px;background:#ddd}.product[_ngcontent-%COMP%]{padding-top:5px;padding-bottom:5px}.product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]{margin-top:15px}.product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   h6[_ngcontent-%COMP%]{color:#455a64}.product.tumbnail[_ngcontent-%COMP%]{box-shadow:0 5px 25px 0 transparent;transition:.3s linear;padding-top:0}.product.tumbnail[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]:hover{box-shadow:0 5px 25px #0003}.single-product[_ngcontent-%COMP%]   span[_ngcontent-%COMP%]{display:inline-block}.single-product[_ngcontent-%COMP%]   .rating[_ngcontent-%COMP%]   .fa-star[_ngcontent-%COMP%], .single-product[_ngcontent-%COMP%]   .rating[_ngcontent-%COMP%]   .fa-star-o[_ngcontent-%COMP%]{font-size:16px;color:#f7d4a0;margin-left:2px}.single-product[_ngcontent-%COMP%]   .rating[_ngcontent-%COMP%] + *[_ngcontent-%COMP%]{margin-left:15px}.single-product[_ngcontent-%COMP%]   h1.h1-variant-2[_ngcontent-%COMP%]{margin-bottom:20px}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]:before{content:"";height:100%;display:inline-block;vertical-align:middle}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   span[_ngcontent-%COMP%]{display:inline-block;vertical-align:middle}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .price[_ngcontent-%COMP%]{font-weight:400}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .price.sale[_ngcontent-%COMP%]{color:#e75854;font-size:33px}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   *[_ngcontent-%COMP%] + .price[_ngcontent-%COMP%]{margin-left:10.8%}@media (max-width: 1199px){.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   *[_ngcontent-%COMP%] + .price[_ngcontent-%COMP%]{margin-left:7.8%}}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   *[_ngcontent-%COMP%] + .quantity[_ngcontent-%COMP%]{margin-left:26px}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .info-list[_ngcontent-%COMP%]{border-bottom:1px solid #f3f3ed;border-top:1px solid #f3f3ed;font-family:Montserrat,sans-serif;padding-top:26px;padding-bottom:26px;text-align:left}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .info-list[_ngcontent-%COMP%]   dt[_ngcontent-%COMP%], .single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .info-list[_ngcontent-%COMP%]   dd[_ngcontent-%COMP%]{display:inline-block;line-height:25px;padding-top:10px;padding-bottom:10px}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .info-list[_ngcontent-%COMP%]   dt[_ngcontent-%COMP%]{letter-spacing:.08em;font-size:12px;color:#a7b0b4;width:35%;text-transform:uppercase}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .info-list[_ngcontent-%COMP%]   dd[_ngcontent-%COMP%]{font-size:15px;color:#565452;width:62.5%}.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .share[_ngcontent-%COMP%]   span.small[_ngcontent-%COMP%]{margin-top:9px}@media (max-width: 991px){.single-product[_ngcontent-%COMP%]   .caption[_ngcontent-%COMP%]   .share[_ngcontent-%COMP%]   span.small[_ngcontent-%COMP%]{display:block;margin-bottom:15px}}@media (max-width: 767px){.single-product[_ngcontent-%COMP%]   .table-mobile[_ngcontent-%COMP%]   tr[_ngcontent-%COMP%]{padding-top:0}.single-product[_ngcontent-%COMP%]   .table-mobile[_ngcontent-%COMP%]   tr[_ngcontent-%COMP%]:before{display:none}}.price[_ngcontent-%COMP%]{display:inline-block;font-size:15px;font-family:Montserrat,sans-serif;font-weight:700;letter-spacing:.02em;color:#2b2f3e}.price.sale[_ngcontent-%COMP%]{color:#e75854}.price[_ngcontent-%COMP%]   del[_ngcontent-%COMP%]{color:#b0bec5}.quantity[_ngcontent-%COMP%]{text-align:center;font-family:Montserrat,sans-serif;font-size:12px;background:#eceff1;padding-top:5px;padding-bottom:5px;width:82px;height:auto;display:inline-block}.quantity[_ngcontent-%COMP%]   span[_ngcontent-%COMP%]{display:inline-block}.quantity[_ngcontent-%COMP%]   .num[_ngcontent-%COMP%]{width:26px}.quantity[_ngcontent-%COMP%]   [class*=fa-][_ngcontent-%COMP%]{padding-top:4px;width:22px;padding-bottom:4px;color:#b0bec5;cursor:pointer}.quantity[_ngcontent-%COMP%]   [class*=fa-][_ngcontent-%COMP%]:hover{color:#455a64}.imgP[_ngcontent-%COMP%]{max-width:300px;max-height:300px}.desc[_ngcontent-%COMP%]{max-height:10px}.product-bloc[_ngcontent-%COMP%]{border:1px solid #c0c0c0;color:#404040;padding:0 10px 10px;margin:20px 0;border-radius:10px;box-shadow:3px 3px 6px #a9a9a9}']}),t})();var O=e(4006),m=e(1281);const x=[{path:"",component:M}];let _=(()=>{class t{}return t.\u0275fac=function(o){return new(o||t)},t.\u0275mod=n.oAB({type:t}),t.\u0275inj=n.cJS({providers:[s,{provide:g.TP,useClass:m.O,multi:!0}],imports:[a.ez,r.Bz.forChild(x),O.u5]}),t})()}}]);