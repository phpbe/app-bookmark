<be-head>
    <?php
    $wwwUrl = \Be\Be::getProperty('App.Bookmark')->getWwwUrl();
    ?>
    <link rel="stylesheet" href="<?php echo $wwwUrl; ?>/admin/css/chapter/chapters.css">
</be-head>


<be-page-content>

    <?php
    $formData = [];
    $uiItems = new \Be\AdminPlugin\UiItem\UiItems();
    $rootUrl = \Be\Be::getRequest()->getRootUrl();
    ?>
    <div class="be-bc-fff be-px-100 be-pt-100 be-pb-50" id="app" v-cloak>
        <div class="left-side" :style="{width: leftWidth + 'px'}">
            <div class="left-side-tree">
                <el-tree
                        ref="categoryTree"
                        :data="categoryTree"
                        node-key="id"
                        @node-click="(data) => editChapter(data.id)"
                        :expand-on-click-node="false"
                        highlight-current
                        draggable>
                <span class="custom-tree-node" slot-scope="{ node, data }">
                    <span>{{ node.name }}</span>
                  </span>
                </el-tree>
            </div>
        </div>

        <div id="left-resize" :style="{left: leftWidth + 'px'}"></div>

        <div class="right-side" :style="{marginLeft: (leftWidth + 10) + 'px'}">
            <el-form ref="formRef" :model="formData">
                <div class="be-row">

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

                formData: null,

                loading: false,

                t: false
                <?php
                echo $uiItems->getVueData();
                ?>
            },
            methods: {
                editChapter(id) {

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
                initFormData: function () {
                    this.formData = {
                        id: "",
                        parent_id: "",
                        title: "",
                        is_enable: 0,
                    };
                },

                autoLoad() {
                    if (this.categoryTree.length > 0) {

                        let chapterId = this.categoryTree[0].id;

                        this.editChapter(chapterId);

                        let _this = this;

                        // 选中新添加的文档
                        _this.$nextTick(function (){
                            _this.$refs.categoryTree.setCurrentKey(chapterId);
                        });
                    }
                }
                <?php
                echo $uiItems->getVueMethods();
                ?>
            }
            <?php
            $uiItems->setVueHook('created', '
                this.initFormData();
            ');

            $uiItems->setVueHook('mounted', 'this.resizeLeft();');

            echo $uiItems->getVueHooks();
            ?>
        });
    </script>

</be-page-content>