<be-head>
    <?php
    $wwwUrl = \Be\Be::getProperty('App.Bookmark')->getWwwUrl();
    ?>
    <link rel="stylesheet" href="<?php echo $wwwUrl; ?>/admin/css/url/manager.css">
</be-head>


<be-page-content>

    <?php
    $formData = [];
    $uiItems = new \Be\AdminPlugin\UiItem\UiItems();
    $rootUrl = \Be\Be::getRequest()->getRootUrl();
    ?>
    <div class="be-bc-fff be-px-100 be-pt-100 be-pb-50 be-p-relative" style="height: calc(100vh - 120px);" id="app" v-cloak>
        <div class="left-side" :style="{width: leftWidth + 'px'}">
            <div class="left-side-tree">
                <el-tree
                        ref="categoryTree"
                        :data="categoryTree"
                        node-key="id"
                        @node-click="(data) => edit(data.id)"
                        :expand-on-click-node="false"
                        :props="categoryTreeProps"
                        highlight-current
                        default-expand-all
                        >
                <span class="custom-tree-node" slot-scope="{ node, data }">
                    <span>{{node.label}}</span>
                  </span>
                </el-tree>
            </div>
        </div>

        <div id="left-resize" :style="{left: leftWidth + 'px'}"></div>

        <div class="right-side" :style="{marginLeft: (leftWidth + 10) + 'px'}">
            <el-form ref="formRef" :model="formData">
                <div class="be-row">

                </div>

                <draggable v-model="formData" force-fallback="true" animation="100" filter=".image-uploader" handle=".image-move">
                    <transition-group>
                        <div v-for="group in formData" :key="group.ordering" class="group be-b-eee">
                            <div class="be-p-50 be-bc-eee">
                                <div class="be-row">
                                    <div class="be-col-auto be-lh-250">组名：</div>
                                    <div class="be-col">
                                        <el-input
                                                type="text"
                                                placeholder="组名"
                                                v-model = "group.name"
                                                size="medium"
                                                maxlength="200">
                                        </el-input>
                                    </div>
                                </div>
                            </div>

                            <div class="be-row">
                                <div class="be-col-12" v-for="url in group.urls">
                                    <div class="be-p-100">
                                        <div class="be-row">
                                            <div class="be-col-auto be-lh-250">名称：</div>
                                            <div class="be-col">
                                                <el-input
                                                        type="text"
                                                        placeholder="组名"
                                                        v-model = "url.name"
                                                        size="medium"
                                                        maxlength="200">
                                                </el-input>
                                            </div>
                                        </div>

                                        <div class="be-row be-mt-50">
                                            <div class="be-col-auto be-lh-250">网址：</div>
                                            <div class="be-col">
                                                <el-input
                                                        type="text"
                                                        placeholder="组名"
                                                        v-model = "url.url"
                                                        size="medium"
                                                        maxlength="200">
                                                </el-input>
                                            </div>
                                            <div class="be-col-auto be-lh-250">账号：</div>
                                            <div class="be-col-auto be-lh-250"></div>
                                        </div>

                                        <div class="be-row be-mt-50">
                                            <div class="be-col-auto be-lh-250">启用：</div>
                                            <div class="be-col-auto be-lh-250">
                                                <el-switch v-model.number="url.is_enable" :active-value="1" :inactive-value="0" size="medium"></el-switch>
                                            </div>
                                            <div class="be-col-auto be-lh-250"><div class="be-pl-100">账号：</div></div>
                                            <div class="be-col-auto be-lh-250">
                                                <el-switch v-model.number="url.has_account" :active-value="1" :inactive-value="0" size="medium"></el-switch>
                                            </div>
                                            <div class="be-col">
                                                <div class="be-pl-100">
                                                    <el-input
                                                            type="text"
                                                            placeholder="用户名"
                                                            v-model = "url.username"
                                                            size="medium"
                                                            maxlength="200">
                                                    </el-input>
                                                </div>
                                            </div>
                                            <div class="be-col">
                                                <div class="be-pl-100">
                                                    <el-input
                                                            type="text"
                                                            placeholder="密码"
                                                            v-model = "url.password"
                                                            size="medium"
                                                            maxlength="200">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="be-p-100">
                                <el-button size="small" type="warning" icon="el-icon-plus" @click="addUrl(group)">新增网址</el-button>
                            </div>

                        </div>

                    </transition-group>
                </draggable>

                <div class="be-mt-100">
                    <el-button size="medium" type="success" icon="el-icon-plus" @click="addGroup">新增分组</el-button>
                    <el-button size="medium" type="primary" icon="el-icon-save" @click="save">保存</el-button>
                </div>
            </el-form>
        </div>
    </div>

    <?php
    echo $uiItems->getJs();
    echo $uiItems->getCss();
    ?>

    <script>
        let vueCenter = new Vue({
            el: '#app',
            data: {
                leftWidth: 200,
                leftWidthLoaded: false,

                categoryTreeCurrentNodeKey: "",
                categoryTree: <?php echo json_encode($this->categoryTree); ?>,
                categoryTreeProps: {
                    children: 'children',
                    label: 'name'
                },

                formData: [],

                loading: false,

                t: false
                <?php
                echo $uiItems->getVueData();
                ?>
            },
            methods: {
                edit(categoryId) {
                    let _this = this;
                    _this.loading = true;
                    _this.$http.post("<?php echo beAdminUrl('Bookmark.Url.getGroupUrls'); ?>", {
                        category_id: categoryId
                    }).then(function (response) {
                        _this.loading = false;
                        if (response.status === 200) {
                            var responseData = response.data;
                            if (responseData.success) {
                                _this.formData = responseData.groupUrls;
                            } else {
                                if (responseData.message) {
                                    _this.$message.error(responseData.message);
                                } else {
                                    _this.$message.error("服务器返回数据异常！");
                                }
                            }
                        }
                    }).catch(function (error) {
                        _this.loading = false;
                        _this.$message.error(error);
                    });
                },
                addUrl(group) {
                    group.urls.push({
                        id: "",
                        group_id: group.id,
                        name: "",
                        url: "",
                        has_account: 0,
                        username: "",
                        password: "",
                        is_enable: 1,
                    });
                },
                addGroup() {

                },
                save() {

                },
                resizeLeft() {
                    let cookieKey = 'doc-chapter-left-width';

                    if (!this.leftWidthLoaded) {
                        if (this.$cookies.isKey(cookieKey)) {
                            let cookieLeftWidth = this.$cookies.get(cookieKey);
                            if (!isNaN(cookieLeftWidth)) {
                                cookieLeftWidth = Number(cookieLeftWidth);
                                if (cookieLeftWidth >= 150 && cookieLeftWidth <= 600) {
                                    this.leftWidth = cookieLeftWidth;
                                }
                            }
                        }
                        this.$cookies.set(cookieKey, this.leftWidth, 86400 * 180);

                        this.leftWidthLoaded = true;
                    }

                    let _this = this;
                    let resize = document.getElementById('left-resize');
                    resize.onmousedown = function (e) {
                        resize.className = 'left-resize-on';
                        resize.left = resize.offsetLeft;
                        let x0 = e.clientX;
                        document.onmousemove = function (e) {
                            let x1 = e.clientX;
                            let letfWidth = resize.left + (x1 - x0);
                            if (letfWidth < 150) letfWidth = 150;
                            if (letfWidth > 600) letfWidth = 600;
                            _this.leftWidth = letfWidth;
                            _this.resizeRight();
                        };
                        document.onmouseup = function (evt) {
                            resize.className = '';
                            document.onmousemove = null;
                            document.onmouseup = null;
                            resize.releaseCapture && resize.releaseCapture();
                            _this.$cookies.set(cookieKey, _this.leftWidth, 86400 * 180);
                        };
                        resize.setCapture && resize.setCapture();
                        return false;
                    };
                },

                autoLoad() {
                    if (this.categoryTree.length > 0) {

                        let categoryId = this.categoryTree[0].id;

                        this.edit(categoryId);

                        let _this = this;

                        // 选中新添加的文档
                        _this.$nextTick(function (){
                            _this.$refs.categoryTree.setCurrentKey(categoryId);
                        });
                    }
                }
                <?php
                echo $uiItems->getVueMethods();
                ?>
            }
            <?php
            $uiItems->setVueHook('mounted', 'this.resizeLeft();');

            echo $uiItems->getVueHooks();
            ?>
        });
    </script>

</be-page-content>