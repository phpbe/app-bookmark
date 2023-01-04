<be-head>
    <?php
    $appSystemWwwUrl = \Be\Be::getProperty('App.System')->getWwwUrl();
    ?>
    <script src="<?php echo $appSystemWwwUrl; ?>/lib/sortable/sortable.min.js"></script>
    <script src="<?php echo $appSystemWwwUrl; ?>/lib/vuedraggable/vuedraggable.umd.min.js"></script>

    <?php
    $appBookmarkWwwUrl = \Be\Be::getProperty('App.Bookmark')->getWwwUrl();
    ?>
    <link rel="stylesheet" href="<?php echo $appBookmarkWwwUrl; ?>/admin/css/category/categories.css" type="text/css"/>
</be-head>


<be-page-content>
    <div class="be-bc-fff be-px-100 be-pt-100 be-pb-50" id="app" v-cloak>
        <el-form ref="categoriesFormRef" :model="formData">

            <div class="be-row categories-header">
                <div class="be-col">
                    <div class="category-col-name be-fw-bold">
                        名称
                    </div>
                </div>
                <div class="be-col-auto">
                    <div class="category-col-is-enable be-fw-bold">
                        是否启用
                    </div>
                </div>
                <div class="be-col-auto">
                    <div class="category-col-op be-fw-bold be-pr-400">
                        操作
                    </div>
                </div>
            </div>

            <div class="categories">
                <draggable
                        v-model="formData.categories"
                        ghost-class="category-ghost"
                        chosen-class="category-chosen"
                        drag-class="category-drag"
                        handle=".category-drag-icon"
                        force-fallback="true"
                        animation="100"
                        @start="dragStart"
                        :move="dragMove"
                        @update="dragUpdate"
                        @end="dragEnd"
                >
                    <transition-group>
                        <div
                                :class="{'be-row': true, 'category': true, 'category-level-2': category.level===2, 'category-level-3': category.level===3, 'category-ghost': dragIndexFrom===categoryIndex}"
                                v-for="category, categoryIndex in formData.categories"
                                :key="category.id">
                            <div class="be-col">
                                <div class="category-col-name">
                                    <div class="be-row">
                                        <div class="be-col-auto category-drag-icon">
                                            <i class="el-icon-rank"></i>
                                        </div>

                                        <div class="be-col">
                                            <el-form-item
                                                    :key="category.id"
                                                    :prop="'categories.' + categoryIndex + '.name'"
                                                    :rules="{required:true,message:'请输入名称',trigger:'blur'}">

                                                <el-input
                                                        style="min-width:200px;max-width:400px;width:80%;"
                                                        type="text"
                                                        placeholder="名称"
                                                        v-model="category.name"
                                                        size="medium"
                                                        maxlength="60">
                                                </el-input>
                                            </el-form-item>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            

                            <div class="be-col-auto">
                                <div class="category-col-is-enable be-pt-50">
                                    <el-switch v-model.number="category.is_enable" :active-value="1" :inactive-value="0" size="medium"></el-switch>
                                </div>
                            </div>

                            <div class="be-col-auto">
                                <div class="category-col-op be-pt-25 be-pr-50">
                                    <template v-if="category.level === 1">
                                        <el-link type="primary" class="be-mr-100" @click="addChildCategory(categoryIndex)">添加二级分类</el-link>
                                    </template>

                                    <template v-else-if="category.level === 2">
                                        <el-link type="primary" class="be-mr-100" @click="addChildCategory(categoryIndex)">添加三级分类</el-link>
                                    </template>

                                    <el-link type="danger" @click="removeCategory(categoryIndex)">删除</el-link>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </draggable>
            </div>
        </el-form>

        <div class="be-my-100">
            <el-button type="success" size="medium" icon="el-icon-plus" @click="addCategory">新增分类</el-button>
            <el-button type="primary" size="medium" icon="el-icon-save" @click="save" :disabled="loading">保存</el-button>
        </div>
    </div>

    <script>
        Vue.component('vuedraggable', window.vuedraggable);

        let vueCenter = new Vue({
            el: '#app',
            components: {
                vuedraggable: window.vuedraggable,//当前页面注册组件
            },
            data: {
                loading: false,
                formData: {
                    categories: <?php echo json_encode($this->flatTree); ?>,
                },

                dragTimer : null,
                dragIndexFrom : null,
                dragIndexTo : null,
                
                t: false
            },
            methods: {
                save: function () {
                    let _this = this;
                    this.$refs["categoriesFormRef"].validate(function (valid) {
                        if (valid) {
                            _this.loading = true;
                            _this.$http.post("<?php echo beAdminUrl('Bookmark.Category.categories'); ?>", {
                                formData: _this.formData
                            }).then(function (response) {
                                _this.loading = false;
                                //console.log(response);
                                if (response.status === 200) {
                                    let responseData = response.data;
                                    if (responseData.success) {
                                        _this.$message.success(responseData.message);
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
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
                        } else {
                            return false;
                        }
                    });
                },
                addCategory: function () {
                    this.formData.categories.push({
                        id: "-" + (this.formData.categories.length - 1),
                        parent_id: "",
                        name: "",
                        is_enable: 1,
                        level: 1
                    });
                },
                addChildCategory: function (categoryIndex) {
                    let category = this.formData.categories[categoryIndex];
                    this.formData.categories.splice(categoryIndex + 1, 0, {
                        id: "-" + (this.formData.categories.length - 1),
                        parent_id: category.id,
                        name: "",
                        is_enable: 1,
                        level: category.level + 1
                    });
                },
                removeCategory: function (categoryIndex) {
                    this.formData.categories.splice(categoryIndex, 1);
                    this.updateCategories();
                },
     
                dragStart(e) {
                    //console.log("dragStart", e);

                    this.dragIndexFrom = e.oldIndex;
                    this.dragIndexTo = e.oldIndex;

                    if (this.dragTimer !== null) {
                        clearInterval(this.dragTimer);
                    }

                    let dragCategory = this.formData.categories[this.dragIndexFrom];
                    let dragLevelFrom = dragCategory.level;

                    let _this = this;
                    this.dragTimer = setInterval(function () {
                        let matrix = _this.$refs.categoriesFormRef.$el.getElementsByClassName('category-drag')[0].style.transform;
                        //console.log("matrix", matrix);
                        // matrix matrix(1, 0, 0, 1, 28.1166, 0.457153)
                        if (matrix.substr(0, 6) === "matrix") {
                            let offsetX = matrix.match(/,\s*([\-\.|0-9]+),\s*[\-\.|0-9]+\)/)[1];
                            //console.log("offsetX", offsetX);

                            // 拖动到的目标层级
                            let dragLevelTo = dragLevelFrom;

                            if (offsetX >= 60) {
                                dragLevelTo += 2;
                            } else if (offsetX >= 30) {
                                dragLevelTo += 1;
                            } else if (offsetX <= -60) {
                                dragLevelTo -= 2;
                            } else if (offsetX <= -30) {
                                dragLevelTo -= 1;
                            }

                            if (dragLevelTo < 1) {
                                dragLevelTo = 1;
                            }

                            if (dragLevelTo > 3) {
                                dragLevelTo = 3;
                            }

                            if (_this.dragIndexTo > 0) {
                                let preCategory = _this.formData.categories[_this.dragIndexTo - 1];
                                if (dragLevelTo > preCategory.level + 1) {
                                    dragLevelTo = preCategory.level + 1;
                                }
                            } else {
                                dragLevelTo = 1;
                            }

                            //console.log("dragLevelTo", dragLevelTo);

                            dragCategory.level = dragLevelTo;
                        }

                    }, 200);
                },
                dragMove(e, originalEvent){
                    //console.log("dragMove", e, originalEvent);
                    this.dragIndexTo = e.draggedContext.futureIndex;
                },
                dragUpdate(e){
                    //console.log("onUpdate", e);
                },
                dragEnd(e){
                    // console.log("onEnd", e);
                    clearInterval(this.dragTimer);

                    this.dragTimer = null;
                    this.dragIndexFrom = null;
                    this.dragIndexTo = null;

                    this.updateCategories();
                },

                updateCategories() {
                    let level1Item = null;
                    let level2Item = null;
                    let preItem = null;
                    let item = null;

                    for (let i=0, len = this.formData.categories.length; i<len; i++) {
                        item = this.formData.categories[i];
                        if (preItem === null) {
                            item.level = 1;
                        } else {
                            // 子类不得比前一个父类层级大1
                            if (item.level > preItem.level + 1) {
                                item.level = preItem.level + 1;
                            }
                        }

                        if (item.level === 1) {
                            item.parent_id = "";
                            level1Item = item;
                        } else if (item.level === 2) {
                            item.parent_id = level1Item.id;
                            level2Item = item;
                        } else if (item.level === 3) {
                            item.parent_id = level2Item.id;
                        }

                        preItem = item;
                    }
                },

                t: function () {
                }
            }
        });

    </script>

</be-page-content>