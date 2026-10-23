	    function gb() {
	        document.getElementById("movID").style.visibility = 'hidden';
	        document.getElementById("mask").style.visibility = 'hidden';
	    }
	    
        function fDragging(obj, e, ismove) {
	        if (document.all) obj.setCapture();
	        if (!e) e = window.event;
	        var objk;
	        if (ismove) {
	            objk = obj.parentNode;
	        } else {
	            objk = obj.parentNode.parentNode.childNodes[1];
	        }
	        var x, y, x_, y_;
	        if (ismove) {
	            x = parseInt(objk.style.left);
	            y = parseInt(objk.style.top);
	        } else {
	            x = parseInt(objk.parentNode.style.width);
	            y = parseInt(objk.style.height);
	        }
	        x_ = e.clientX;
	        y_ = e.clientY;
	        if (document.addEventListener) {
	            document.addEventListener('mousemove', inFmove, true);
	            document.addEventListener('mouseup', inFup, true);
	        } else if (document.attachEvent) {
	            document.attachEvent('onmousemove', inFmove);
	            document.attachEvent('onmouseup', inFup);
	        }
	        inFstop(e);
	        inFabort(e);
	        function inFmove(e) {
	            var evt;
	            if (!e) e = window.event;
	            var x1, y1;
	            if (ismove) {
	                x1 = e.clientX - x_ + x;
	                y1 = e.clientY - y_ + y;
	                if (x1 < 0) x1 = 0;
	                if (y1 < 0) y1 = 0;
	                objk.style.left = x1 + 'px';
	                objk.style.top = y1 + 'px';
	            } else {
	                x1 = e.clientX - x_;
	                y1 = e.clientY - y_;
	                if (Math.abs(x1) < 5) return;
	                if (Math.abs(y1) < 5) return;
	                var x2 = x1 + x;
	                var y2 = y1 + y;
	                if (x2 < 100) x2 = 100;
	                if (y2 < 50) y2 = 50;
	                objk.parentNode.style.width = x2 + "px";
	                objk.style.height = y2 + "px";
	            }
	            inFstop(e);
	        }
	        function inFup(e) {
	            var evt;
	            if (!e) e = window.event;

	            if (document.removeEventListener) {
	                document.removeEventListener('mousemove', inFmove, true);
	                document.removeEventListener('mouseup', inFup, true);
	            } else if (document.detachEvent) {
	                document.detachEvent('onmousemove', inFmove);
	                document.detachEvent('onmouseup', inFup);
	            }
	            if (document.all) obj.releaseCapture();
	            inFstop(e);
	        }

	        function inFstop(e) {
	            if (e.stopPropagation) return e.stopPropagation();
	            else return e.cancelBubble = true;
	        }

	        function inFabort(e) {
	            if (e.preventDefault) return e.preventDefault();
	            else return e.returnValue = false;
	        }
	    }


function error() {
		art.dialog.tips('功能尚未完成，敬请期待！',2);}