(function(){var d=!1,t=function(e){return e instanceof t?e:this instanceof t?void(this.EXIFwrapped=e):new t(e)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=t),exports.EXIF=t):this.EXIF=t;var u=t.Tags={36864:"ExifVersion",40960:"FlashpixVersion",40961:"ColorSpace",40962:"PixelXDimension",40963:"PixelYDimension",37121:"ComponentsConfiguration",37122:"CompressedBitsPerPixel",37500:"MakerNote",37510:"UserComment",40964:"RelatedSoundFile",36867:"DateTimeOriginal",36868:"DateTimeDigitized",37520:"SubsecTime",37521:"SubsecTimeOriginal",37522:"SubsecTimeDigitized",33434:"ExposureTime",33437:"FNumber",34850:"ExposureProgram",34852:"SpectralSensitivity",34855:"ISOSpeedRatings",34856:"OECF",37377:"ShutterSpeedValue",37378:"ApertureValue",37379:"BrightnessValue",37380:"ExposureBias",37381:"MaxApertureValue",37382:"SubjectDistance",37383:"MeteringMode",37384:"LightSource",37385:"Flash",37396:"SubjectArea",37386:"FocalLength",41483:"FlashEnergy",41484:"SpatialFrequencyResponse",41486:"FocalPlaneXResolution",41487:"FocalPlaneYResolution",41488:"FocalPlaneResolutionUnit",41492:"SubjectLocation",41493:"ExposureIndex",41495:"SensingMethod",41728:"FileSource",41729:"SceneType",41730:"CFAPattern",41985:"CustomRendered",41986:"ExposureMode",41987:"WhiteBalance",41988:"DigitalZoomRation",41989:"FocalLengthIn35mmFilm",41990:"SceneCaptureType",41991:"GainControl",41992:"Contrast",41993:"Saturation",41994:"Sharpness",41995:"DeviceSettingDescription",41996:"SubjectDistanceRange",40965:"InteroperabilityIFDPointer",42016:"ImageUniqueID"},c=t.TiffTags={256:"ImageWidth",257:"ImageHeight",34665:"ExifIFDPointer",34853:"GPSInfoIFDPointer",40965:"InteroperabilityIFDPointer",258:"BitsPerSample",259:"Compression",262:"PhotometricInterpretation",274:"Orientation",277:"SamplesPerPixel",284:"PlanarConfiguration",530:"YCbCrSubSampling",531:"YCbCrPositioning",282:"XResolution",283:"YResolution",296:"ResolutionUnit",273:"StripOffsets",278:"RowsPerStrip",279:"StripByteCounts",513:"JPEGInterchangeFormat",514:"JPEGInterchangeFormatLength",301:"TransferFunction",318:"WhitePoint",319:"PrimaryChromaticities",529:"YCbCrCoefficients",532:"ReferenceBlackWhite",306:"DateTime",270:"ImageDescription",271:"Make",272:"Model",305:"Software",315:"Artist",33432:"Copyright"},f=t.GPSTags={0:"GPSVersionID",1:"GPSLatitudeRef",2:"GPSLatitude",3:"GPSLongitudeRef",4:"GPSLongitude",5:"GPSAltitudeRef",6:"GPSAltitude",7:"GPSTimeStamp",8:"GPSSatellites",9:"GPSStatus",10:"GPSMeasureMode",11:"GPSDOP",12:"GPSSpeedRef",13:"GPSSpeed",14:"GPSTrackRef",15:"GPSTrack",16:"GPSImgDirectionRef",17:"GPSImgDirection",18:"GPSMapDatum",19:"GPSDestLatitudeRef",20:"GPSDestLatitude",21:"GPSDestLongitudeRef",22:"GPSDestLongitude",23:"GPSDestBearingRef",24:"GPSDestBearing",25:"GPSDestDistanceRef",26:"GPSDestDistance",27:"GPSProcessingMethod",28:"GPSAreaInformation",29:"GPSDateStamp",30:"GPSDifferential"},g=t.StringValues={ExposureProgram:{0:"Not defined",1:"Manual",2:"Normal program",3:"Aperture priority",4:"Shutter priority",5:"Creative program",6:"Action program",7:"Portrait mode",8:"Landscape mode"},MeteringMode:{0:"Unknown",1:"Average",2:"CenterWeightedAverage",3:"Spot",4:"MultiSpot",5:"Pattern",6:"Partial",255:"Other"},LightSource:{0:"Unknown",1:"Daylight",2:"Fluorescent",3:"Tungsten (incandescent light)",4:"Flash",9:"Fine weather",10:"Cloudy weather",11:"Shade",12:"Daylight fluorescent (D 5700 - 7100K)",13:"Day white fluorescent (N 4600 - 5400K)",14:"Cool white fluorescent (W 3900 - 4500K)",15:"White fluorescent (WW 3200 - 3700K)",17:"Standard light A",18:"Standard light B",19:"Standard light C",20:"D55",21:"D65",22:"D75",23:"D50",24:"ISO studio tungsten",255:"Other"},Flash:{0:"Flash did not fire",1:"Flash fired",5:"Strobe return light not detected",7:"Strobe return light detected",9:"Flash fired, compulsory flash mode",13:"Flash fired, compulsory flash mode, return light not detected",15:"Flash fired, compulsory flash mode, return light detected",16:"Flash did not fire, compulsory flash mode",24:"Flash did not fire, auto mode",25:"Flash fired, auto mode",29:"Flash fired, auto mode, return light not detected",31:"Flash fired, auto mode, return light detected",32:"No flash function",65:"Flash fired, red-eye reduction mode",69:"Flash fired, red-eye reduction mode, return light not detected",71:"Flash fired, red-eye reduction mode, return light detected",73:"Flash fired, compulsory flash mode, red-eye reduction mode",77:"Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",79:"Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",89:"Flash fired, auto mode, red-eye reduction mode",93:"Flash fired, auto mode, return light not detected, red-eye reduction mode",95:"Flash fired, auto mode, return light detected, red-eye reduction mode"},SensingMethod:{1:"Not defined",2:"One-chip color area sensor",3:"Two-chip color area sensor",4:"Three-chip color area sensor",5:"Color sequential area sensor",7:"Trilinear sensor",8:"Color sequential linear sensor"},SceneCaptureType:{0:"Standard",1:"Landscape",2:"Portrait",3:"Night scene"},SceneType:{1:"Directly photographed"},CustomRendered:{0:"Normal process",1:"Custom process"},WhiteBalance:{0:"Auto white balance",1:"Manual white balance"},GainControl:{0:"None",1:"Low gain up",2:"High gain up",3:"Low gain down",4:"High gain down"},Contrast:{0:"Normal",1:"Soft",2:"Hard"},Saturation:{0:"Normal",1:"Low saturation",2:"High saturation"},Sharpness:{0:"Normal",1:"Soft",2:"Hard"},SubjectDistanceRange:{0:"Unknown",1:"Macro",2:"Close view",3:"Distant view"},FileSource:{3:"DSC"},Components:{0:"",1:"Y",2:"Cb",3:"Cr",4:"R",5:"G",6:"B"}};function o(e){return!!e.exifdata}function r(r,o){function t(e){var t=i(e),n=function(e){var t=new DataView(e);d&&console.log("Got file of length "+e.byteLength);if(255!=t.getUint8(0)||216!=t.getUint8(1))return d&&console.log("Not a valid JPEG"),!1;var n=2,r=e.byteLength;for(;n<r;){if(l=n,56===(s=t).getUint8(l)&&66===s.getUint8(l+1)&&73===s.getUint8(l+2)&&77===s.getUint8(l+3)&&4===s.getUint8(l+4)&&4===s.getUint8(l+5)){var o=t.getUint8(n+7);o%2!=0&&(o+=1),0===o&&(o=4);var i=n+8+o,a=t.getUint16(n+6+o);return m(e,i,a)}n++}var s,l}(e);r.exifdata=t||{},r.iptcdata=n||{},o&&o.call(r)}if(r.src)if(/^data\:/i.test(r.src))t(function(e,t){t=t||e.match(/^data\:([^\;]+)\;base64,/im)[1]||"",e=e.replace(/^data\:([^\;]+)\;base64,/gim,"");for(var n=atob(e),r=n.length,o=new ArrayBuffer(r),i=new Uint8Array(o),a=0;a<r;a++)i[a]=n.charCodeAt(a);return o}(r.src));else if(/^blob\:/i.test(r.src)){(n=new FileReader).onload=function(e){t(e.target.result)},function(e,t){var n=new XMLHttpRequest;n.open("GET",e,!0),n.responseType="blob",n.onload=function(e){200!=this.status&&0!==this.status||t(this.response)},n.send()}(r.src,function(e){n.readAsArrayBuffer(e)})}else{var e=new XMLHttpRequest;e.onload=function(){if(200!=this.status&&0!==this.status)throw"Could not load image";t(e.response),e=null},e.open("GET",r.src,!0),e.responseType="arraybuffer",e.send(null)}else if(window.FileReader&&(r instanceof window.Blob||r instanceof window.File)){var n;(n=new FileReader).onload=function(e){d&&console.log("Got file of length "+e.target.result.byteLength),t(e.target.result)},n.readAsArrayBuffer(r)}}function i(e){var t=new DataView(e);if(d&&console.log("Got file of length "+e.byteLength),255!=t.getUint8(0)||216!=t.getUint8(1))return d&&console.log("Not a valid JPEG"),!1;for(var n,r=2,o=e.byteLength;r<o;){if(255!=t.getUint8(r))return d&&console.log("Not a valid marker at offset "+r+", found: "+t.getUint8(r)),!1;if(n=t.getUint8(r+1),d&&console.log(n),225==n)return d&&console.log("Found 0xFFE1 marker"),a(t,r+4,t.getUint16(r+2));r+=2+t.getUint16(r+2)}}var h={120:"caption",110:"credit",25:"keywords",55:"dateCreated",80:"byline",85:"bylineTitle",122:"captionWriter",105:"headline",116:"copyright",15:"category"};function m(e,t,n){for(var r,o,i,a,s=new DataView(e),l={},u=t;u<t+n;)28===s.getUint8(u)&&2===s.getUint8(u+1)&&(a=s.getUint8(u+2))in h&&((i=s.getInt16(u+3))+5,o=h[a],r=P(s,u+5,i),l.hasOwnProperty(o)?l[o]instanceof Array?l[o].push(r):l[o]=[l[o],r]:l[o]=r),u++;return l}function p(e,t,n,r,o){var i,a,s,l=e.getUint16(n,!o),u={};for(s=0;s<l;s++)i=n+12*s+2,!(a=r[e.getUint16(i,!o)])&&d&&console.log("Unknown tag: "+e.getUint16(i,!o)),u[a]=S(e,i,t,n,o);return u}function S(e,t,n,r,o){var i,a,s,l,u,d,c=e.getUint16(t+2,!o),f=e.getUint32(t+4,!o),g=e.getUint32(t+8,!o)+n;switch(c){case 1:case 7:if(1==f)return e.getUint8(t+8,!o);for(i=4<f?g:t+8,a=[],l=0;l<f;l++)a[l]=e.getUint8(i+l);return a;case 2:return P(e,i=4<f?g:t+8,f-1);case 3:if(1==f)return e.getUint16(t+8,!o);for(i=2<f?g:t+8,a=[],l=0;l<f;l++)a[l]=e.getUint16(i+2*l,!o);return a;case 4:if(1==f)return e.getUint32(t+8,!o);for(a=[],l=0;l<f;l++)a[l]=e.getUint32(g+4*l,!o);return a;case 5:if(1==f)return u=e.getUint32(g,!o),d=e.getUint32(g+4,!o),(s=new Number(u/d)).numerator=u,s.denominator=d,s;for(a=[],l=0;l<f;l++)u=e.getUint32(g+8*l,!o),d=e.getUint32(g+4+8*l,!o),a[l]=new Number(u/d),a[l].numerator=u,a[l].denominator=d;return a;case 9:if(1==f)return e.getInt32(t+8,!o);for(a=[],l=0;l<f;l++)a[l]=e.getInt32(g+4*l,!o);return a;case 10:if(1==f)return e.getInt32(g,!o)/e.getInt32(g+4,!o);for(a=[],l=0;l<f;l++)a[l]=e.getInt32(g+8*l,!o)/e.getInt32(g+4+8*l,!o);return a}}function P(e,t,r){var o="";for(n=t;n<t+r;n++)o+=String.fromCharCode(e.getUint8(n));return o}function a(e,t){if("Exif"!=P(e,t,4))return d&&console.log("Not valid EXIF data! "+P(e,t,4)),!1;var n,r,o,i,a,s=t+6;if(18761==e.getUint16(s))n=!1;else{if(19789!=e.getUint16(s))return d&&console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)"),!1;n=!0}if(42!=e.getUint16(s+2,!n))return d&&console.log("Not valid TIFF data! (no 0x002A)"),!1;var l=e.getUint32(s+4,!n);if(l<8)return d&&console.log("Not valid TIFF data! (First offset less than 8)",e.getUint32(s+4,!n)),!1;if((r=p(e,s,s+l,c,n)).ExifIFDPointer)for(o in i=p(e,s,s+r.ExifIFDPointer,u,n)){switch(o){case"LightSource":case"Flash":case"MeteringMode":case"ExposureProgram":case"SensingMethod":case"SceneCaptureType":case"SceneType":case"CustomRendered":case"WhiteBalance":case"GainControl":case"Contrast":case"Saturation":case"Sharpness":case"SubjectDistanceRange":case"FileSource":i[o]=g[o][i[o]];break;case"ExifVersion":case"FlashpixVersion":i[o]=String.fromCharCode(i[o][0],i[o][1],i[o][2],i[o][3]);break;case"ComponentsConfiguration":i[o]=g.Components[i[o][0]]+g.Components[i[o][1]]+g.Components[i[o][2]]+g.Components[i[o][3]]}r[o]=i[o]}if(r.GPSInfoIFDPointer)for(o in a=p(e,s,s+r.GPSInfoIFDPointer,f,n)){switch(o){case"GPSVersionID":a[o]=a[o][0]+"."+a[o][1]+"."+a[o][2]+"."+a[o][3]}r[o]=a[o]}return r}t.getData=function(e,t){return!((e instanceof Image||e instanceof HTMLImageElement)&&!e.complete)&&(o(e)?t&&t.call(e):r(e,t),!0)},t.getTag=function(e,t){if(o(e))return e.exifdata[t]},t.getAllTags=function(e){if(!o(e))return{};var t,n=e.exifdata,r={};for(t in n)n.hasOwnProperty(t)&&(r[t]=n[t]);return r},t.pretty=function(e){if(!o(e))return"";var t,n=e.exifdata,r="";for(t in n)n.hasOwnProperty(t)&&("object"==typeof n[t]?n[t]instanceof Number?r+=t+" : "+n[t]+" ["+n[t].numerator+"/"+n[t].denominator+"]\r\n":r+=t+" : ["+n[t].length+" values]\r\n":r+=t+" : "+n[t]+"\r\n");return r},t.readFromBinaryFile=function(e){return i(e)},"function"==typeof define&&define.amd&&define("exif-js",[],function(){return t})}).call(this);