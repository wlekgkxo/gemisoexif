/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/mediameta.js":
/*!***********************************!*\
  !*** ./resources/js/mediameta.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var _this = this;
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
// document.getElementById('checked_all').addEventListener('change', (e) => {});

// document.getElementById('ingest_request_call').addEventListener('submit', (e) => {
//     // 인제스트 요청 버튼 클릭 시, 이미지 정보 Json 파일로 생성 하는 api call
//     let datas = localStorage.getItem('ingest_media_assets'),
//         form_data = new FormData();

//     form_data.append('datas', datas);

//     console.log(datas);

// media upload 및 정보 가져오기
// let req = new XMLHttpRequest();

// req.onreadystatechange = () => {
//     if(req.readyState === XMLHttpRequest.DONE) {
//         if(req.status === 200) {
//             let result = JSON.parse(req.response);

//             console.log(result);
//         } else {
//             console.log('request error');
//         }
//     } 
// }

// req.open('POST', 'requestingest', true);
// // req.responseType = "json";
// req.send(form_data);
// });

// 클릭 -> 업로드
document.getElementById('upload_media').addEventListener('change', function (e) {
  var files = event.target.files,
    form_data = new FormData();
  if (files.length > 50) alert('파일은 최대 50개만 가능합니다.');

  // console.log(document.getElementById('prepend_row').children().length);

  for (var i = 0; i < files.length; i++) {
    form_data.append('files[]', files[i]);
  }
  callUploadMedia(form_data);
});

// Drag Enter 올려둔 상태
// document.getElementById('drag_media').addEventListener('dragenter', (e) => {});

// Drag over 상태
document.getElementById('drag_media').addEventListener('dragover', function (e) {
  e.preventDefault();
  _this.style.backgroundColor = '#c1c1c1';
});

// Drag leave 상태
// document.getElementById('drag_media').addEventListener('dragleave', (e) => {});

// 드래그&드롭 -> 업로드
document.getElementById('drag_media').addEventListener('drop', function (e) {
  e.preventDefault();

  // console.log('drop');
  // this.style.backgroundColor = 'white';

  // console.dir(e.dataTransfer);

  // var data = e.dataTransfer.files;
  // console.dir(data);

  var files = e.dataTransfer.files,
    form_data = new FormData();
  if (files.length > 50) alert('파일은 최대 50개만 가능합니다.');
  for (var i = 0; i < files.length; i++) {
    form_data.append('files[]', files[i]);
  }
  callUploadMedia(form_data);
  _this.style.backgroundColor = 'white';
});

// document ready
document.addEventListener('DOMContentLoaded', function (e) {
  var progress_request = localStorage.getItem('ingest_media_assets');
  if (progress_request) {
    if (progress_request !== '') {
      if (!confirm('진행하시던 인제스트 요청 작업이 있습니다. 이어서 하시겠습니까?')) {
        removeProgressIngest(progress_request);
      } else {
        stillProgressIngest(progress_request);
      }
    }
  } else {
    localStorage.setItem('ingest_media_assets', '');
  }
});
function callUploadMedia(form_data) {
  // media upload 및 정보 가져오기
  var req = new XMLHttpRequest();
  req.upload.addEventListener('progress', progressHandler, false);
  req.upload.addEventListener("progress", progressHandler, false);
  req.addEventListener("load", completeHandler, false);
  req.addEventListener("error", errorHandler, false);
  req.addEventListener("abort", abortHandler, false);
  req.onreadystatechange = function () {
    if (req.readyState === XMLHttpRequest.DONE) {
      if (req.status === 200) {
        var result = JSON.parse(req.response);
        if (!localStorage.getItem('ingest_media_assets')) {
          localStorage.setItem('ingest_media_assets', JSON.stringify(result));
          pushRecord(result);
        } else {
          var storage_assets = localStorage.getItem('ingest_media_assets'),
            assets = JSON.parse(storage_assets);
          localStorage.setItem('ingest_media_assets', JSON.stringify([].concat(_toConsumableArray(result), _toConsumableArray(assets))));
          pushRecord(result);
        }
      } else {
        console.log('request error');
      }
    }
  };
  req.open('POST', 'mediaupload', true);
  // req.responseType = "json";
  req.send(form_data);
}
/* progress bar 관련 */
function progressHandler(event) {
  var percent = event.loaded / event.total * 100;
  document.getElementById('progress_bar').value = Math.round(percent);
}
function completeHandler(event) {
  document.getElementById('progress_bar').value = 0;
}
function errorHandler(event) {}
function abortHandler(event) {}
/* progress bar 관련 */

/* DOMContentLoaded 에서 사용되는 함수들*/
function stillProgressIngest() {
  // 이어서 정보 입력하기
  var progress_request = localStorage.getItem('ingest_media_assets'),
    datas = JSON.parse(progress_request);
  pushRecord(datas);
}
function removeProgressIngest(datas) {
  // 이어서 정보 입력 안함
  var form_data = new FormData();
  form_data.append('datas', datas);
  var req = new XMLHttpRequest();
  req.onreadystatechange = function () {
    if (req.readyState === XMLHttpRequest.DONE) {
      if (req.status === 200) {
        var result = JSON.parse(req.response);
        localStorage.removeItem('ingest_media_assets');
      } else {
        console.log('request error');
      }
    }
  };
  req.open('POST', 'removemedia', true);
  // req.responseType = "json";
  req.send(form_data);
}
/* callUploadMedia 에서 사용되는 함수들*/

/* DOM 생성 관련 함수 callUploadMedia 에서 사용 */
function pushRecord(datas) {
  // 메타 입력 카드 생성
  var rows = '';
  for (var i = 0; i < datas.length; i++) {
    rows += createRecord(datas[i], i);
  }
  document.getElementById('prepend_row').insertAdjacentHTML('afterbegin', rows);

  /* 분류 시작 */
  var classifies = document.querySelectorAll('.media-class-select');
  classifies.forEach(function (classific) {
    classific.addEventListener('change', function () {
      var cidx = event.target.parentElement.dataset.midx,
        this_val = event.target.value;
      addDynamicData(cidx, 'classif', this_val);
    });
  });
  /* 분류 끝 */

  /* 태그입력 시작 */
  var tags = document.querySelectorAll('.insert-tag-input');
  tags.forEach(function (tag) {
    tag.addEventListener('keydown', function (e) {
      var idx = parseInt(event.target.parentElement.dataset.midx),
        tag_box = document.getElementById('tag_insert_box_' + idx),
        this_val = event.target.value.trim();
      if (event.key === 'Enter') {
        if (this_val === '') return false;
        var type_val = this_val.replace(/ /g, "_");
        if (addDynamicArrayData(idx, 'tags', type_val)) {
          document.getElementById('media_tags_' + idx).insertAdjacentHTML('beforeend', '<option value="' + type_val + '" selected>' + type_val + '</option>');
          tag_box.querySelector('.tag-blocks').lastChild.insertAdjacentHTML('beforebegin', '<span><p>' + type_val + '</p><button type="button" onclick="javascript:removeTag(' + idx + ', ' + "'" + type_val + "'" + ');">X</button></span>');
        }
        event.target.value = '';
      }
      if (event.key === 'Backspace') {
        if (event.target.value === '') {
          if (tag_box.querySelector('.tag-blocks').children.length === 1) return false;
          if (tag_box.children.length === 0) return false;
          var text_val = event.target.parentElement.previousElementSibling.querySelector('p').innerText;
          removeTag(idx, text_val);
          event.target.parentElement.previousElementSibling.remove();
          event.target.value = text_val.replaceAll('_', ' ') + ' ';
        }
      }
    });
  });

  // 태그 입력 focus
  for (var _i = 0; _i < prepend_row.children.length; _i++) {
    document.getElementById('tag_insert_box_' + _i).addEventListener('click', function () {
      event.target.querySelector('span > input').focus();
    });
  }
  /* 태그입력 끝 */

  /* 카테고리 시작 */
  var categories = document.querySelectorAll('.media-categories');
  categories.forEach(function (category) {
    category.addEventListener('change', function (e) {
      var cate_val = event.target.value,
        checked = event.target.checked,
        parent_idx = event.target.parentElement.parentElement.dataset.midx;
      if (checked) {
        addDynamicArrayData(parent_idx, 'categories', cate_val);
      } else {
        removeDynamicArrayData(parent_idx, 'categories', cate_val);
      }
    });
  });
  /* 카테고리 끝 */
}

// 태그 삭제
function removeTag(idx, rmtag) {
  var tags = document.getElementById('media_tags_' + idx).children,
    remove_idx = removeDynamicArrayData(idx, 'tags', rmtag);
  tags[remove_idx].remove();
}
function addDynamicData(idx, name, value) {
  // 단일 string 동적 데이터 생성 및 변경
  var progress_request = localStorage.getItem('ingest_media_assets'),
    datas = JSON.parse(progress_request),
    data = datas[idx];
  if (!('dynamic' in data)) data.dynamic = {};
  if (!(name in data.dynamic)) data.dynamic[name] = '';
  data.dynamic[name] = value;
  datas[idx] = data;
  localStorage.setItem('ingest_media_assets', JSON.stringify(datas));
  return true;
}
function addDynamicArrayData(idx, name, value) {
  // Array 형식 동적 데이터 추가
  var progress_request = localStorage.getItem('ingest_media_assets'),
    datas = JSON.parse(progress_request),
    data = datas[idx];
  if (!('dynamic' in data)) data.dynamic = {};
  if (!(name in data.dynamic)) data.dynamic[name] = [];
  data.dynamic[name].push(value);
  datas[idx] = data;
  localStorage.setItem('ingest_media_assets', JSON.stringify(datas));
  return true;
}
function removeDynamicArrayData(idx, name, value) {
  // Array 형식 동적 데이터 삭제
  var progress_request = localStorage.getItem('ingest_media_assets'),
    datas = JSON.parse(progress_request),
    data = datas[idx];
  if (!('dynamic' in data)) return false;
  if (!(name in data.dynamic)) return false;
  var remove_idx = data.dynamic[name].indexOf(value);
  data.dynamic[name] = data.dynamic[name].filter(function (element) {
    return element !== value;
  });
  datas[idx] = data;
  localStorage.setItem('ingest_media_assets', JSON.stringify(datas));
  return remove_idx;
}

// 메타 입력 카드 생성
function createRecord(data, idx) {
  var media = data.media,
    dynamic = {},
    classifies_static = ['미분류', '인물사진', '풍경사진', '무대사진'],
    classif = '',
    categories_static = ['인물', '동물', '식음료', '건물/랜드마크', '뷰티/패션', '공연', '운동', '풍경', '문화', '도심', '비즈니스', '일러스트/클립아트', '기타'],
    categories = [];
  if ('dynamic' in data) {
    dynamic = data.dynamic;
    if (dynamic['classif']) classif = dynamic['classif'];
    if (dynamic['categories']) categories = dynamic['categories'];
  }
  var record = '<tr>' + '<td><input type="checkbox" name="check_media_id[]" value="' + idx + '" /></td>' + '<td><img src="' + media.thumbnail + '" /></td>' + '<td><p>[필수 입력항목]</p>' + '<ul><li><span>파일명</span>' + '<span><input type="text" name="file_name_' + idx + '" value="' + media.original_name + '" /></span>' + '</li><li>' + '<span>사진분류</span>' + '<span data-midx="' + idx + '">' + '<select name="media_class_' + idx + '" class="media-class-select">' + '<option>선택 ▼</option>';
  classifies_static.forEach(function (classific) {
    record += '<option value="' + classific + '" ' + selectedMark(classif === classific) + '>' + classific + '</option>';
  });
  record += '</select></span>' + '</li><li>' + '<span>카테고리</span>' + '<span data-midx="' + idx + '">';
  var wrap_i = 1;
  categories_static.forEach(function (category) {
    record += '<label>' + '<input type="checkbox" name="media_categories_' + idx + '[]" class="media-categories"' + checkedMark(categories.includes(category)) + ' value="' + category + '" />' + category + '</label><label>';
    if (wrap_i !== 1 && wrap_i % 3 === 0) {
      record += '<br/>';
    }
    wrap_i++;
  });
  record += '</li><li>' + '<span>태그' + '<select name="media_tags_' + idx + '[]" id="media_tags_' + idx + '" multiple style="display:none;">';
  if ('dynamic' in data) {
    if ('tags' in data.dynamic) {
      data.dynamic.tags.forEach(function (tag) {
        record += '<option value="' + tag + '" selected>' + tag + '</option>';
      });
    }
  }
  record += '</select>' + '</span>' + '<span class="sh-world">#</span>' + '<span class="tag-insert-box" id="tag_insert_box_' + idx + '">' + '<div class="tag-blocks">';
  if ('dynamic' in data) {
    if ('tags' in data.dynamic) {
      data.dynamic.tags.forEach(function (tag) {
        record += '<span>' + '<p>' + tag + '</p>' + '<button type="button" onclick="javascript:removeTag(' + idx + ', ' + "'" + tag + "'" + ');">X</button></span>';
      });
    }
  }
  record += '<span data-midx="' + idx + '"><input type="text" class="insert-tag-input" value="" /></span></div>' + '</span></li>' + '<li>' + '<span>사진설명</span>' + '<span class="textarea-box">' + '<textarea name="media_description_' + idx + '" placeholder="ex) 2022 MAMA에서 수상소감을 이야기하는 블랙핑크"></textarea>' + '</span></li>' + '</ul>' + '</td>' + '</tr>';
  return record;
}
function checkedMark(check) {
  if (check) return 'checked';
  return '';
}
function selectedMark(select) {
  if (select) return 'selected';
  return '';
}
/* DOM 생성 관련 함수 callUploadMedia 에서 사용 */

/***/ }),

/***/ 1:
/*!*****************************************!*\
  !*** multi ./resources/js/mediameta.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! c:\Projects\exif-web\resources\js\mediameta.js */"./resources/js/mediameta.js");


/***/ })

/******/ });