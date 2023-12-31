/*!
 * a-tools 1.5.1
 *
 * Copyright (c) 2009 Andrey Kramarev(andrey.kramarev[at]ampparit.com), Ampparit Inc. (www.ampparit.com)
 * Licensed under the MIT license.
 * http://www.ampparit.fi/a-tools/license.txt
 *
 * Basic usage:

    <textarea></textarea>
    <input type="text" />

    // Get current selection
    var sel = $("textarea").getSelection()

    // Replace current selection
    $("input").replaceSelection("foo");

    // Count characters
    alert($("textarea").countCharacters());

    // Set max length without callback function
    $("textarea").setMaxLength(7);

    // Set max length with callback function which will be called when limit is exceeded
    $("textarea").setMaxLength(10, function() {
        alert("hello")
    });

    // Removing limit:
    $("textarea").setMaxLength(-1);

    // Insert text at current caret position
    $("#textarea").insertAtCaretPos("hello");

    // Set caret position (1 = beginning, -1 = end)
    $("#textArea").setCaretPos(10);

    // Set Selection
    $("#textArea").setSelection(10,15);

 */
 var caretPositionAmp=[]; function init(){if(navigator.appName=="Microsoft Internet Explorer"){obj=document.getElementsByTagName("TEXTAREA");var b,a=0;for(a=0;a<obj.length;a++){b=obj[a];caretPositionAmp[a]=b.value.length;b.onmouseup=function(){b=document.activeElement;for(var c=0;c<obj.length;c++)if(obj[c]==b)break;b.focus();var e=document.selection.createRange(),h=b.createTextRange(),d=h.duplicate();h.moveToBookmark(e.getBookmark());d.setEndPoint("EndToStart",h);caretPositionAmp[c]=d.text.length};b.onkeyup=function(){b=document.activeElement; for(var c=0;c<obj.length;c++)if(obj[c]==b)break;b.focus();var e=document.selection.createRange(),h=b.createTextRange(),d=h.duplicate();h.moveToBookmark(e.getBookmark());d.setEndPoint("EndToStart",h);caretPositionAmp[c]=d.text.length}}}}window.onload=init; jQuery.fn.extend({getSelection:function(){var b=this.jquery?this[0]:this,a,c,e,h=0;b.onmousedown=function(){document.selection&&typeof b.selectionStart!="number"?document.selection.empty():window.getSelection().removeAllRanges()};if(document.selection){var d=document.selection.createRange(),f=0,g=0,i=0;a=document.getElementsByTagName("TEXTAREA");for(c=0;c<a.length;c++)if(a[c]==b)break;if(b.value.match(/\n/g)!=null)h=b.value.match(/\n/g).length;if(d.text){e=d.text;if(typeof b.selectionStart=="number"){a= b.selectionStart;c=b.selectionEnd;if(a==c)return{start:a,end:c,text:d.text,length:c-a}}else{a=b.createTextRange();e=a.duplicate();firstRe=a.text;a.moveToBookmark(d.getBookmark());secondRe=a.text;e.setEndPoint("EndToStart",a);if(firstRe==secondRe&&firstRe!=d.text||e.text.length>firstRe.length)return{start:caretPositionAmp[c],end:caretPositionAmp[c],text:"",length:0};a=e.text.length;c=e.text.length+d.text.length}if(h>0)for(e=0;e<=h;e++){var k=b.value.indexOf("\n",g);if(k!=-1&&k<a){g=k+1;f++;i=f}else if(k!= -1&&k>=a&&k<=c)if(k==a+1){f--;i--;g=k+1}else{g=k+1;i++}else e=h}if(d.text.indexOf("\n",0)==1)i+=2;a-=f;c-=i;return{start:a,end:c,text:d.text,length:c-a}}b.focus();if(typeof b.selectionStart=="number")a=b.selectionStart;else{d=document.selection.createRange();a=b.createTextRange();e=a.duplicate();a.moveToBookmark(d.getBookmark());e.setEndPoint("EndToStart",a);a=e.text.length}if(h>0)for(e=0;e<=h;e++){k=b.value.indexOf("\n",g);if(k!=-1&&k<a){g=k+1;f++}else e=h}a-=f;if(a==0&&typeof b.selectionStart!= "number"){a=caretPositionAmp[c];c=caretPositionAmp[c]}return{start:a,end:a,text:d.text,length:0}}else if(typeof b.selectionStart=="number"){a=b.selectionStart;c=b.selectionEnd;e=b.value.substring(b.selectionStart,b.selectionEnd);return{start:a,end:c,text:e,length:c-a}}else return{start:undefined,end:undefined,text:undefined,length:undefined}},replaceSelection:function(b){var a=this.jquery?this[0]:this,c,e;e=0;var h,d,f=0,g=0,i=a.scrollTop==undefined?0:a.scrollTop;c=document.getElementsByTagName("TEXTAREA"); for(var k=0;k<c.length;k++)if(c[k]==a)break;if(document.selection&&typeof a.selectionStart!="number"){i=document.selection.createRange();if(typeof a.selectionStart!="number"){var j;d=a.createTextRange();h=d.duplicate();c=d.text;d.moveToBookmark(i.getBookmark());j=d.text;try{h.setEndPoint("EndToStart",d)}catch(m){return this}if(c==j&&c!=i.text||h.text.length>c.length)return this}if(i.text){part=i.text;if(a.value.match(/\n/g)!=null)f=a.value.match(/\n/g).length;c=h.text.length;if(f>0)for(j=0;j<=f;j++){var l= a.value.indexOf("\n",e);if(l!=-1&&l<c){e=l+1;g++}else j=f}i.text=b;caretPositionAmp[k]=h.text.length+b.length;d.move("character",caretPositionAmp[k]);document.selection.empty();a.blur()}return this}else if(typeof a.selectionStart=="number"&&a.selectionStart!=a.selectionEnd){c=a.selectionStart;e=a.selectionEnd;a.value=a.value.substr(0,c)+b+a.value.substr(e);e=c+b.length;a.setSelectionRange(e,e);a.scrollTop=i;return this}return this},setSelection:function(b,a){b=parseInt(b);a=parseInt(a);var c=this.jquery? this[0]:this;c.focus();if(typeof c.selectionStart!="number"){re=c.createTextRange();if(re.text.length<a)a=re.text.length+1}if(a<b)return this;if(document.selection){var e=0,h=0,d=0,f=0;if(typeof c.selectionStart!="number"){re.collapse(true);re.moveEnd("character",a);re.moveStart("character",b);re.select()}else{if(typeof c.selectionStart=="number"){if(c.value.match(/\n/g)!=null)e=c.value.match(/\n/g).length;if(e>0)for(var g=0;g<=e;g++){var i=c.value.indexOf("\n",d);if(i!=-1&&i<b){d=i+1;h++;f=h}else if(i!= -1&&i>=b&&i<=a)if(i==b+1){h--;f--;d=i+1}else{d=i+1;f++}else g=e}b+=h;a+=f;c.selectionStart=b;c.selectionEnd=a}c.focus()}return this}else if(c.selectionStart||c.selectionStart==0){c.focus();window.getSelection().removeAllRanges();c.selectionStart=b;c.selectionEnd=a;c.focus();return this}},insertAtCaretPos:function(b){var a=this.jquery?this[0]:this,c,e,h,d,f,g,i;c=e=0;var k=a.scrollTop==undefined?0:a.scrollTop;i=document.getElementsByTagName("TEXTAREA");for(var j=0;j<i.length;j++)if(i[j]==a)break;a.focus(); if(document.selection&&typeof a.selectionStart!="number"){if(a.value.match(/\n/g)!=null)c=a.value.match(/\n/g).length;i=parseInt(caretPositionAmp[j]);if(c>0)for(var m=0;m<=c;m++){var l=a.value.indexOf("\n",h);if(l!=-1&&l<=i){h=l+1;i-=1;e++}}}caretPositionAmp[j]=parseInt(caretPositionAmp[j]);a.onkeyup=function(){if(document.selection&&typeof a.selectionStart!="number"){a.focus();d=document.selection.createRange();f=a.createTextRange();g=f.duplicate();f.moveToBookmark(d.getBookmark());g.setEndPoint("EndToStart", f);caretPositionAmp[j]=g.text.length}};a.onmouseup=function(){if(document.selection&&typeof a.selectionStart!="number"){a.focus();d=document.selection.createRange();f=a.createTextRange();g=f.duplicate();f.moveToBookmark(d.getBookmark());g.setEndPoint("EndToStart",f);caretPositionAmp[j]=g.text.length}};if(document.selection&&typeof a.selectionStart!="number"){d=document.selection.createRange();if(d.text.length!=0)return this;f=a.createTextRange();textLength=f.text.length;g=f.duplicate();f.moveToBookmark(d.getBookmark()); g.setEndPoint("EndToStart",f);c=g.text.length;if(caretPositionAmp[j]>0&&c==0){e=caretPositionAmp[j]-e;f.move("character",e);f.select();d=document.selection.createRange();caretPositionAmp[j]+=b.length}else if(!(caretPositionAmp[j]>=0)&&textLength==0){d=document.selection.createRange();caretPositionAmp[j]=b.length+textLength}else if(!(caretPositionAmp[j]>=0)&&c==0){f.move("character",textLength);f.select();d=document.selection.createRange();caretPositionAmp[j]=b.length+textLength}else if(!(caretPositionAmp[j]>= 0)&&c>0){f.move("character",0);document.selection.empty();f.select();d=document.selection.createRange();caretPositionAmp[j]=c+b.length}else if(caretPositionAmp[j]>=0&&caretPositionAmp[j]==textLength){if(textLength!=0){f.move("character",textLength);f.select()}else f.move("character",0);d=document.selection.createRange();caretPositionAmp[j]=b.length+textLength}else{if(caretPositionAmp[j]>=0&&c!=0&&caretPositionAmp[j]>=c){e=caretPositionAmp[j]-c;f.move("character",e)}else caretPositionAmp[j]>=0&&c!= 0&&caretPositionAmp[j]<c&&f.move("character",0);document.selection.empty();f.select();d=document.selection.createRange();caretPositionAmp[j]+=b.length}d.text=b;a.focus();return this}else if(typeof a.selectionStart=="number"&&a.selectionStart==a.selectionEnd){h=a.selectionStart+b.length;c=a.selectionStart;e=a.selectionEnd;a.value=a.value.substr(0,c)+b+a.value.substr(e);a.setSelectionRange(h,h);a.scrollTop=k;return this}return this},setCaretPos:function(b){var a=this.jquery?this[0]:this,c,e=0,h=0,d; d=document.getElementsByTagName("TEXTAREA");for(var f=0;f<d.length;f++)if(d[f]==a)break;a.focus();if(parseInt(b)==0)return this;if(parseInt(b)>0){b=parseInt(b)-1;if(document.selection&&typeof a.selectionStart=="number"&&a.selectionStart==a.selectionEnd){if(a.value.match(/\n/g)!=null)e=a.value.match(/\n/g).length;if(e>0)for(var g=0;g<=e;g++){d=a.value.indexOf("\n",c);if(d!=-1&&d<=b){c=d+1;b=parseInt(b)+1}}}}else if(parseInt(b)<0){b=parseInt(b)+1;if(document.selection&&typeof a.selectionStart!="number"){b= a.value.length+parseInt(b);if(a.value.match(/\n/g)!=null)e=a.value.match(/\n/g).length;if(e>0){for(g=0;g<=e;g++){d=a.value.indexOf("\n",c);if(d!=-1&&d<=b){c=d+1;b=parseInt(b)-1;h+=1}}b=b+h-e}}else if(document.selection&&typeof a.selectionStart=="number"){b=a.value.length+parseInt(b);if(a.value.match(/\n/g)!=null)e=a.value.match(/\n/g).length;if(e>0){b=parseInt(b)-e;for(g=0;g<=e;g++){d=a.value.indexOf("\n",c);if(d!=-1&&d<=b){c=d+1;b=parseInt(b)+1;h+=1}}}}else b=a.value.length+parseInt(b)}else return this; if(document.selection&&typeof a.selectionStart!="number"){c=document.selection.createRange();if(c.text!=0)return this;a=a.createTextRange();a.collapse(true);a.moveEnd("character",b);a.moveStart("character",b);a.select();caretPositionAmp[f]=b;return this}else if(typeof a.selectionStart=="number"&&a.selectionStart==a.selectionEnd){a.setSelectionRange(b,b);return this}return this},countCharacters:function(){var b=this.jquery?this[0]:this;if(b.value.match(/\r/g)!=null)return b.value.length-b.value.match(/\r/g).length; return b.value.length},setMaxLength:function(b,a){this.each(function(){var c=this.jquery?this[0]:this,e=c.type,h,d;if(parseInt(b)<0)b=1E8;if(e=="text")c.maxLength=b;if(e=="textarea"||e=="text"){c.onkeypress=function(f){var g=c.value.match(/\r/g);d=b;if(g!=null)d=parseInt(d)+g.length;f=f||event;g=f.keyCode;h=document.selection?document.selection.createRange().text.length>0:c.selectionStart!=c.selectionEnd;if(c.value.length>=d&&(g>47||g==32||g==0||g==13)&&!f.ctrlKey&&!f.altKey&&!h){c.value=c.value.substring(0, d);typeof a=="function"&&a();return false}};c.onkeyup=function(){var f=c.value.match(/\r/g),g=0,i=0;d=b;if(f!=null){for(var k=0;k<=f.length;k++)if(c.value.indexOf("\n",i)<=parseInt(b)){g++;i=c.value.indexOf("\n",i)+1}d=parseInt(b)+g}if(c.value.length>d){c.value=c.value.substring(0,d);typeof a=="function"&&a();return this}}}else return this});return this}});

 /*
 * jQuery textarea suggest plugin
 *
 * Copyright (c) 2009-2010 Roman Imankulov
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Requires:
 *   - jQuery (tested with 1.3.x and 1.4.x)
 *   - jquery.a-tools >= 1.4.1 (http://plugins.jquery.com/project/a-tools)
 */

/*globals jQuery,document */

(function ($) {
    // workaround for Opera browser
    if ($.browser.opera) {
        $(document).keypress(function (e) {
            if ($.asuggestFocused) {
                $.asuggestFocused.focus();
                $.asuggestFocused = null;
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    $.asuggestKeys = {
        UNKNOWN: 0,
        SHIFT: 16,
        CTRL: 17,
        ALT: 18,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40,
        DEL: 46,
        TAB: 9,
        RETURN: 13,
        ESC: 27,
        COMMA: 188,
        PAGEUP: 33,
        PAGEDOWN: 34,
        BACKSPACE: 8,
        SPACE: 32
    };
    $.asuggestFocused = null;

    $.fn.asuggest = function (suggests, options) {
        return this.each(function () {
            $.makeSuggest(this, suggests, options);
        });
    };

    $.fn.asuggest.defaults = {
        'delimiters': '\n ',
        'minChunkSize': 1,
        'cycleOnTab': true,
        'autoComplete': true,
        'endingSymbols': ' ',
        'stopSuggestionKeys': [$.asuggestKeys.RETURN, $.asuggestKeys.SPACE],
        'ignoreCase': true
    };

    /* Make suggest:
     *
     * create and return jQuery object on the top of DOM object
     * and store suggests as part of this object
     *
     * @param area: HTML DOM element to add suggests to
     * @param suggests: The array of suggest strings
     * @param options: The options object
     */
    $.makeSuggest = function (area, suggests, options) {
        options = $.extend({}, $.fn.asuggest.defaults, options);

        var KEY = $.asuggestKeys,
            $area = $(area);
        $area.suggests = suggests;
        $area.options = options;

        /* Internal method: get the chunk of text before the cursor */
        $area.getChunk = function () {
            var delimiters = this.options.delimiters.split(), // array of chars
                textBeforeCursor = this.val().substr(0, this.getSelection().start),
                indexOfDelimiter = -1,
                i,
                d,
                idx;
            for (i = 0; i < delimiters.length; i++) {
                d = delimiters[i];
                idx = textBeforeCursor.lastIndexOf(d);
                if (idx > indexOfDelimiter) {
                    indexOfDelimiter = idx;
                }
            }
            if (indexOfDelimiter < 0) {
                return textBeforeCursor;
            } else {
                return textBeforeCursor.substr(indexOfDelimiter + 1);
            }
        };

        /* Internal method: get completion.
         * If performCycle is true then analyze getChunk() and and getSelection()
         */
        $area.getCompletion = function (performCycle) {
            var text = this.getChunk(),
                selectionText = this.getSelection().text,
                suggests = this.suggests,
                foundAlreadySelectedValue = false,
                firstMatchedValue = null,
                i,
                suggest;
            // search the variant
            for (i = 0; i < suggests.length; i++) {
                suggest = suggests[i];
                if ($area.options.ignoreCase) {
                    suggest = suggest.toLowerCase();
                    text = text.toLowerCase();
                }
                // some variant is found
                if (suggest.indexOf(text) === 0) {
                    if (performCycle) {
                        if (text + selectionText === suggest) {
                            foundAlreadySelectedValue = true;
                        } else if (foundAlreadySelectedValue) {
                            return suggest.substr(text.length);
                        } else if (firstMatchedValue === null) {
                            firstMatchedValue = suggest;
                        }
                    } else {
                        return suggest.substr(text.length);
                    }
                }
            }
            if (performCycle && firstMatchedValue) {
                return firstMatchedValue.substr(text.length);
            } else {
                return null;
            }
        };

        $area.updateSelection = function (completion) {
            if (completion) {
                var _selectionStart = $area.getSelection().start,
                    _selectionEnd = _selectionStart + completion.length;
                if ($area.getSelection().text === "") {
                    if ($area.val().length === _selectionStart) { // Weird IE workaround, I really have no idea why it works
                        $area.setCaretPos(_selectionStart + 10000);
                    }
                    $area.insertAtCaretPos(completion);
                } else {
                    $area.replaceSelection(completion);
                }
                $area.setSelection(_selectionStart, _selectionEnd);
            }
        };

        $area.keydown(function (e) {
            if (e.keyCode === KEY.TAB) {
                if ($area.options.cycleOnTab) {
                    var chunk = $area.getChunk();
                    if (chunk.length >= $area.options.minChunkSize) {
                        $area.updateSelection($area.getCompletion(true));
                    }
                    e.preventDefault();
                    e.stopPropagation();
                    $area.focus();
                    $.asuggestFocused = this;
                    return false;
                }
            }
            // Check for conditions to stop suggestion
            if ($area.getSelection().length &&
                    $.inArray(e.keyCode, $area.options.stopSuggestionKeys) !== -1) {
                // apply suggestion. Clean up selection and insert a space
                var _selectionEnd = $area.getSelection().end +
                        $area.options.endingSymbols.length;
                var _text = $area.getSelection().text +
                        $area.options.endingSymbols;
                $area.replaceSelection(_text);
                $area.setSelection(_selectionEnd, _selectionEnd);
                e.preventDefault();
                e.stopPropagation();
                this.focus();
                $.asuggestFocused = this;
                return false;
            }
        });

        $area.keyup(function (e) {
            var hasSpecialKeys = e.altKey || e.metaKey || e.ctrlKey,
                hasSpecialKeysOrShift = hasSpecialKeys || e.shiftKey;
            switch (e.keyCode) {
            case KEY.UNKNOWN: // Special key released
            case KEY.SHIFT:
            case KEY.CTRL:
            case KEY.ALT:
            case KEY.RETURN: // we don't want to suggest when RETURN key has pressed (another IE workaround)
                break;
            case KEY.TAB:
                if (!hasSpecialKeysOrShift && $area.options.cycleOnTab) {
                    break;
                }
            case KEY.ESC:
            case KEY.BACKSPACE:
            case KEY.DEL:
            case KEY.UP:
            case KEY.DOWN:
            case KEY.LEFT:
            case KEY.RIGHT:
                if (!hasSpecialKeysOrShift && $area.options.autoComplete) {
                    $area.replaceSelection("");
                }
                break;
            default:
                if (!hasSpecialKeys && $area.options.autoComplete) {
                    var chunk = $area.getChunk();
                    if (chunk.length >= $area.options.minChunkSize) {
                        $area.updateSelection($area.getCompletion(false));
                    }
                }
                break;
            }
        });
        return $area;
    };
}(jQuery));
