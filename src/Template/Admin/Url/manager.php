<be-head>
    <?php
    $appSystemWwwUrl = \Be\Be::getProperty('App.System')->getWwwUrl();
    ?>
    <script src="<?php echo $appSystemWwwUrl; ?>/lib/sortable/sortable.min.js"></script>
    <script src="<?php echo $appSystemWwwUrl; ?>/lib/vuedraggable/vuedraggable.umd.min.js"></script>

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
            <el-form ref="formRef" :model="formData" style="height: 100%;">
                <div class="be-row" style="height: 100%;">
                    <div class="be-col-10" style="height: 100%; overflow-y: auto;">
                        <draggable
                                v-model="formData"
                                handle=".group-drag-icon"
                                force-fallback="true"
                                animation="100"

                                ghost-class="group-ghost"
                                chosen-class="group-chosen"
                                drag-class="group-drag"
                        >
                            <transition-group>
                                <div v-for="group in formData" :key="group.id" :class="{'group': true, 'group-on': currentGroup && currentGroup.id===group.id}" @click="toggleGroup(group)">
                                    <div class="be-row">
                                        <div class="be-col-auto group-drag-icon">
                                            <i class="el-icon-rank"></i>
                                        </div>
                                        <div class="be-col-auto be-lh-250">组名：</div>
                                        <div class="be-col">
                                            <el-input
                                                    type="text"
                                                    placeholder="组名"
                                                    v-model = "group.name"
                                                    size="medium"
                                                    maxlength="300">
                                            </el-input>
                                        </div>
                                        <div class="be-col-auto be-lh-250"><div class="be-pl-100">启用：</div></div>
                                        <div class="be-col-auto be-lh-250">
                                            <el-switch v-model.number="group.is_enable" :active-value="1" :inactive-value="0" size="medium"></el-switch>
                                        </div>
                                        <div class="be-col-auto be-lh-250">
                                            <div class="be-pl-100">
                                                <el-link type="danger" icon="el-icon-delete" @click="deleteGroup(group)"></el-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>
                        </draggable>

                        <div class="be-mt-100">
                            <el-button size="medium" type="success" icon="el-icon-plus" @click="addGroup">新增分组</el-button>
                            <el-button size="medium" type="primary" icon="el-icon-save" @click="save">保存</el-button>
                        </div>
                    </div>
                    <div class="be-col-14" style="height: 100%; overflow-y: auto;">
                        <div class="be-pl-100" v-if="currentGroup !== false">

                            <draggable v-model="currentGroup.urls" handle=".group-url-drag-icon" force-fallback="true" animation="100">
                                <transition-group>

                                    <div v-for="url in currentGroup.urls" :key="url.id" class="group-url">
                                        <div class="be-row">
                                            <div class="be-col-auto group-url-drag-icon">
                                                <i class="el-icon-rank"></i>
                                            </div>
                                            <div class="be-col">

                                                <div class="be-row">
                                                    <div class="be-col-auto be-lh-250">名称：</div>
                                                    <div class="be-col">
                                                        <el-input
                                                                type="text"
                                                                placeholder="名称"
                                                                v-model = "url.name"
                                                                size="medium"
                                                                maxlength="300">
                                                        </el-input>
                                                    </div>
                                                    <div class="be-col-auto be-lh-250"><div class="be-pl-100">网址：</div></div>
                                                    <div class="be-col">
                                                        <el-input
                                                                type="text"
                                                                placeholder="网址"
                                                                v-model = "url.url"
                                                                size="medium"
                                                                maxlength="300">
                                                        </el-input>
                                                    </div>
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
                                                    <div class="be-col" v-if="url.has_account === 1">
                                                        <div class="be-pl-100">
                                                            <el-input
                                                                    type="text"
                                                                    placeholder="用户名"
                                                                    v-model = "url.username"
                                                                    size="medium"
                                                                    maxlength="60">
                                                            </el-input>
                                                        </div>
                                                    </div>
                                                    <div class="be-col" v-if="url.has_account === 1">
                                                        <div class="be-pl-100">
                                                            <el-input
                                                                    type="text"
                                                                    placeholder="密码"
                                                                    v-model = "url.password"
                                                                    size="medium"
                                                                    maxlength="60">
                                                            </el-input>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="be-col-auto">
                                                <div class="be-pl-100 be-lh-250">
                                                    <el-link type="danger" icon="el-icon-delete" @click="deleteUrl(url)"></el-link>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </transition-group>
                            </draggable>

                            <el-button size="small" type="warning" icon="el-icon-plus" @click="addUrl()">新增网址</el-button>
                        </div>
                    </div>
                </div>

            </el-form>
        </div>
    </div>

    <?php
    echo $uiItems->getJs();
    echo $uiItems->getCss();
    ?>

    <script>
        Vue.component('vuedraggable', window.vuedraggable);

        let vueCenter = new Vue({
            el: '#app',
            components: {
                vuedraggable: window.vuedraggable,//当前页面注册组件
            },
            data: {
                leftWidth: 200,
                leftWidthLoaded: false,

                categoryTreeCurrentNodeKey: "",
                categoryTree: <?php echo json_encode($this->categoryTree); ?>,
                categoryTreeProps: {
                    children: 'children',
                    label: 'name'
                },

                categoryId: false,
                formData: [],

                currentGroup: false,

                loading: false,

                t: false
                <?php
                echo $uiItems->getVueData();
                ?>
            },
            methods: {
                edit(categoryId) {
                    this.currentGroup = false;

                    let _this = this;
                    _this.loading = true;
                    _this.$http.post("<?php echo beAdminUrl('Bookmark.Url.getGroupUrls'); ?>", {
                        category_id: categoryId
                    }).then(function (response) {
                        _this.loading = false;
                        if (response.status === 200) {
                            var responseData = response.data;
                            if (responseData.success) {
                                _this.categoryId = categoryId;

                                let groupUrls = [];
                                for(let groupUrl of responseData.groupUrls) {
                                    groupUrl.toggle = 1;
                                    groupUrls.push(groupUrl);
                                }
                                _this.formData = groupUrls;

                                if (_this.formData.length >0) {
                                    _this.currentGroup = _this.formData[0];
                                }
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
                save() {
                    let _this = this;
                    _this.loading = true;
                    _this.$http.post("<?php echo beAdminUrl('Bookmark.Url.edit'); ?>", {
                        category_id: _this.categoryId,
                        formData: _this.formData
                    }).then(function (response) {
                        _this.loading = false;
                        if (response.status === 200) {
                            var responseData = response.data;
                            if (responseData.success) {
                                _this.$message.success(responseData.message);
                                _this.edit(_this.categoryId);
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
                toggleGroup(group) {
                    this.currentGroup = group;
                },
                addGroup() {
                    this.formData.push({
                        id: "-" + (this.formData.length + 1),
                        name: "",
                        is_enable: 1,
                        urls: [],
                    });

                    this.currentGroup = this.formData[this.formData.length - 1];
                },
                deleteGroup(group) {
                    if (group.id === this.currentGroup.id) {
                        this.currentGroup = false;
                    }
                    this.formData.splice(this.formData.indexOf(group), 1);
                },
                addUrl() {
                    this.currentGroup.urls.push({
                        id: "-" + (this.currentGroup.urls.length + 1),
                        group_id: this.currentGroup.id,
                        name: "",
                        url: "",
                        has_account: 0,
                        username: "",
                        password: "",
                        is_enable: 1,
                    });
                },
                deleteUrl(url) {
                    this.currentGroup.urls.splice(this.currentGroup.urls.indexOf(url), 1);
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