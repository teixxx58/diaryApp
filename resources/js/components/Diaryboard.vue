<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-body">
              <div>
                <button type="button" class="btn btn-primary" @click.prevent="addDialogShow()">日記作成</button>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover" style="word-break:break-all;">
                <tbody>
                  <tr>
                    <th>日付</th>
                    <th class="col-7">日記</th>
                    <th>添付画像</th>
                    <th>編集</th>
                  </tr>
                  <tr v-for="item in tabledata.data" :key="item.id">
                    <td>{{ item.created_at }}</td>
                    <td class="col-7">{{ item.diary_text }}</td>
                    <td>
                      <div v-if="item.photo">
                        <img :src="`img/public/${item.photo}`" class="profile-user-img img-fluid img-circle"
                          style="height:40px; width:40px;">
                      </div>
                    </td>
                    <td>
                      <span style='font-size:10px;color:blue; cursor:pointer;'
                        @click.prevent="editPhotoModal(item)">編集</span>
                      |
                      <span style="font-size:10px;color:red; cursor:pointer;"
                        @click.prevent="deletePhoto(item.id)">削除</span>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- set that here after finish get data from backend  -->
              <pagination :data="tabledata" @pagination-change-page="getResults"></pagination>
              <dev class="d-flex justify-content-center">
                <nav aria-label="Page navigation">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous" @click.prevent="goPrevPage()">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next" @click.prevent="goNextPage()">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </dev>
            </div>
            <!-- this is Modal to edit or update -->
            <div class="modal" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 id="addMode" class="modal-title" style="display:none">日記作成</h5>
                    <h5 id="editMode" class="modal-title" style="display:none">編集</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                      @click.prevent="closeDialog()">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="updateDiary" name="updateDiary" enctype="multipart/form-data">
                    <div class="modal-body">
                      <div class="form-group">
                        <!-- here is formm -->
                        <input id="diary_text" v-model="formm.diary_text" type="text" name="diaryText"
                          placeholder="今日晴れ．．．" class="form-control" maxlength="150" required>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-12">
                          <input type="file" accept="image/*" @change='uploadPh' name="photo" class="form-control"
                            id="inputPhoto">
                        </div>
                      </div>
                      <div v-if="user.photo" class="mt-3">
                        <img :src="`img/public/${user.photo}`" class="figure-img img-fluid rounded"
                          style="max-height:100px;" id="diary_photo">
                      </div>
                      <div v-if="image" class="mt-3 im" id="mybox">
                        <img :src="image" class="figure-img img-fluid rounded i" style="max-height:100px;"
                          id="diary_prephoto">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal"
                        @click.prevent="closeDialog()">閉じる</button>
                      <button id="editBtn" type="button" class="btn btn-primary" @click.prevent="UpdatePhoto(formm.id)"
                        style="display:none">保存</button>
                      <button id="newBtn" type="button" class="btn btn-primary" @click.prevent="newDiaryUpload()"
                        style="display:none">登録</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
import $ from 'jquery';
export default {
  name: "diaryboard",
  data() {
    return {
      numbers: '',
      page: 1,
      pag: '',
      tabledata: {},
      user: {},
      formm: new Form({
        id: '',
        diary_text: '',
        photo: '',
      }),
      imageprevi: null,
      image: null,
    }
  },
  methods: {
    uploadPh(e) {
      let file = e.target.files[0];
      let reader = new FileReader();

      if (file['size'] < 2111775) {
        reader.onloadend = (file) => {
          //console.log('RESULT', reader.result)
          this.formm.photo = reader.result;
        }
        reader.readAsDataURL(file);
        reader.onload = e => {
          this.image = e.target.result;
          $('.im').show();

        };
      } else {
        alert('ファイルサイズが2 MBを超えてます。')
      }
    },
    newDiaryUpload() {
      const data = new FormData(updateDiary);
      axios.post('api/st', data)
        .then(() => {
          Toast.fire({
            icon: 'success',
            title: '登録処理完了しました。'
          })
          this.closeDialog();
          this.getResults(null);
        })
        .catch(() => {
          console.log("Error.....")
        });
    },
    getResults(page) {
      let current_page = this.tabledata.current_page;
      if (!page) {
        if (!current_page) {
          page = 1;
        } else {
          page = current_page;
        }
      }
      axios.get('api/photo?page=' + page)
        .then(response => {
          this.tabledata = response.data;
          this.tabledata.data.forEach((e) => {
            e.created_at = new Date(e.created_at).toLocaleDateString();
          });
        });


    },

    //Edit Option  show
    editPhotoModal(item) {
      this.formm.clear();
      this.formm.reset();
      axios.get('api/show/' + item.id)
        .then(response => {
          this.user = response.data;
        });
      document.getElementById('addNew').style.display = 'block';
      document.getElementById('addMode').style.display = 'none';
      document.getElementById('newBtn').style.display = 'none';
      document.getElementById('editMode').style.display = '';
      document.getElementById('editBtn').style.display = '';
      this.formm.fill(item);
    },

    closeDialog() {
      document.getElementById('addNew').style.display = 'none';
      document.getElementById('addMode').style.display = 'none';
      document.getElementById('newBtn').style.display = 'none';
      document.getElementById('editMode').style.display = 'none';
      document.getElementById('editBtn').style.display = 'none';
      document.getElementById('inputPhoto').value = '';
      document.getElementById('diary_photo')?.setAttribute('src', '');
      document.getElementById('diary_prephoto')?.setAttribute('src', '');

    },
    UpdatePhoto(id) {
      const data = new FormData(updateDiary);
      data.append('id', id);
      axios.post('api/update', data)
        .then(() => {
          Toast.fire({
            icon: 'success',
            title: '編集処理完了しました。'
          })
          this.getResults(null);
          this.closeDialog();
        })
        .catch(() => {
          console.log("Error.....")
        })
    },
    deletePhoto(id) {
      //Send Request to server
      axios.post('api/delete/' + id, { id: this.formm.id })
        .then((response) => {
          Toast.fire({
            icon: 'success',
            title: '削除処理完了しました。'
          });
          this.getResults(null);

        }).catch(() => {
          console.log("Error.....");
        })
    },
    addDialogShow() {
      document.getElementById('addNew').style.display = 'block';
      document.getElementById('addMode').style.display = '';
      document.getElementById('newBtn').style.display = '';
      document.getElementById('editMode').style.display = 'none';
      document.getElementById('editBtn').style.display = 'none';
      document.getElementById('diary_text').value = '';
      document.getElementById('inputPhoto').value = '';
      document.getElementById('diary_photo')?.setAttribute('src', '');
      document.getElementById('diary_prephoto')?.setAttribute('src', '');
      this.formm.clear();
      this.formm.reset();
    },
    goNextPage() {
      let last_page = this.tabledata.last_page;
      let current_page = this.tabledata.current_page;
      let page = current_page + 1;
      if (current_page + 1 > last_page) {
        page = last_page;
      }
      this.getResults(page);
    },
    goPrevPage() {
      let current_page = this.tabledata.current_page;
      let page = current_page - 1;
      if (current_page - 1 < 1) {
        page = 1;
      }
      this.getResults(page);
    },

  },
  created() {
    //LoadTableData
    this.getResults(null)
  },
}
</script>
