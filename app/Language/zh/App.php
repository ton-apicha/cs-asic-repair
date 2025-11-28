<?php

/**
 * Chinese Language File (简体中文)
 * ASIC Repair Management System
 */

return [
    // ========================================================================
    // Authentication
    // ========================================================================
    'login'              => '登录',
    'logout'             => '退出',
    'username'           => '用户名',
    'password'           => '密码',
    'enterUsername'      => '请输入用户名',
    'enterPassword'      => '请输入密码',
    'rememberMe'         => '记住我',
    'invalidCredentials' => '用户名或密码错误',
    'pleaseLogin'        => '请登录后继续',
    'loggedOut'          => '您已成功退出',
    'welcomeBack'        => '欢迎回来，{0}！',
    'accessDenied'       => '访问被拒绝。您没有权限访问此页面。',

    // ========================================================================
    // Navigation
    // ========================================================================
    'dashboard'      => '仪表板',
    'management'     => '管理',
    'jobs'           => '维修工单',
    'allJobs'        => '所有工单',
    'kanbanBoard'    => '看板',
    'newJob'         => '新建工单',
    'customers'      => '客户',
    'assets'         => '设备',
    'inventory'      => '零件库存',
    'reports'        => '报表',
    'settings'       => '设置',
    'systemSettings' => '系统设置',
    'branches'       => '分店',
    'users'          => '用户管理',

    // ========================================================================
    // Reports
    // ========================================================================
    'salesReport'    => '销售报表',
    'profitReport'   => '利润报表',
    'warrantyReport' => '保修报表',
    'wipReport'      => '在修工单',
    'kpiReport'      => 'KPI指标',

    // ========================================================================
    // Common Actions
    // ========================================================================
    'create'   => '创建',
    'edit'     => '编辑',
    'update'   => '更新',
    'delete'   => '删除',
    'save'     => '保存',
    'cancel'   => '取消',
    'search'   => '搜索',
    'filter'   => '筛选',
    'export'   => '导出',
    'print'    => '打印',
    'view'     => '查看',
    'back'     => '返回',
    'close'    => '关闭',
    'confirm'  => '确认',
    'actions'  => '操作',
    'submit'   => '提交',
    'reset'    => '重置',
    'refresh'  => '刷新',
    'add'      => '添加',
    'remove'   => '移除',

    // ========================================================================
    // Common Labels
    // ========================================================================
    'id'          => '编号',
    'name'        => '名称',
    'email'       => '邮箱',
    'phone'       => '电话',
    'address'     => '地址',
    'status'      => '状态',
    'date'        => '日期',
    'createdAt'   => '创建时间',
    'updatedAt'   => '更新时间',
    'createdBy'   => '创建人',
    'description' => '描述',
    'notes'       => '备注',
    'total'       => '合计',
    'amount'      => '金额',
    'quantity'    => '数量',
    'price'       => '价格',
    'type'        => '类型',
    'all'         => '全部',
    'active'      => '启用',
    'inactive'    => '停用',
    'yes'         => '是',
    'no'          => '否',

    // ========================================================================
    // Customer
    // ========================================================================
    'customer'          => '客户',
    'customerName'      => '客户名称',
    'customerPhone'     => '电话号码',
    'customerEmail'     => '邮箱地址',
    'customerAddress'   => '地址',
    'customerTaxId'     => '税号',
    'newCustomer'       => '新建客户',
    'editCustomer'      => '编辑客户',
    'customerDetails'   => '客户详情',
    'customerHistory'   => '客户历史',
    'searchCustomer'    => '按名称或电话搜索客户...',
    'noCustomersFound'  => '未找到客户',
    'customerCreated'   => '客户创建成功',
    'customerUpdated'   => '客户更新成功',
    'customerDeleted'   => '客户删除成功',

    // ========================================================================
    // Asset
    // ========================================================================
    'asset'             => '设备',
    'assetBrandModel'   => '品牌/型号',
    'assetSerialNumber' => '序列号',
    'assetMacAddress'   => 'MAC地址',
    'assetHashRate'     => '算力 (TH/s)',
    'assetCondition'    => '外观状况',
    'assetStatus'       => '设备状态',
    'newAsset'          => '新建设备',
    'editAsset'         => '编辑设备',
    'assetDetails'      => '设备详情',
    'assetHistory'      => '维修历史',
    'searchAsset'       => '按序列号搜索...',
    'noAssetsFound'     => '未找到设备',
    'assetCreated'      => '设备创建成功',
    'assetUpdated'      => '设备更新成功',
    'assetDeleted'      => '设备删除成功',
    'createJobFromAsset' => '创建维修工单',

    // Asset Statuses
    'assetStatusStored'    => '寄存中',
    'assetStatusRepairing' => '维修中',
    'assetStatusRepaired'  => '已修好',
    'assetStatusReturned'  => '已取回',

    // ========================================================================
    // Job Card
    // ========================================================================
    'job'              => '工单',
    'jobCard'          => '维修工单',
    'jobId'            => '工单号',
    'jobDate'          => '日期',
    'jobSymptom'       => '故障描述',
    'jobDiagnosis'     => '诊断结果',
    'jobSolution'      => '维修方案',
    'jobNotes'         => '备注',
    'jobStatus'        => '状态',
    'jobTechnician'    => '技术员',
    'jobBranch'        => '分店',
    'newJobCard'       => '新建工单',
    'editJobCard'      => '编辑工单',
    'jobDetails'       => '工单详情',
    'jobParts'         => '使用零件',
    'jobPayments'      => '付款记录',
    'searchJob'        => '按工单号搜索...',
    'noJobsFound'      => '未找到工单',
    'jobCreated'       => '工单创建成功',
    'jobUpdated'       => '工单更新成功',
    'jobCancelled'     => '工单已取消',
    'printCheckinSlip' => '打印接收单',
    'printLabel'       => '打印标签 (A4)',
    'symptomPlaceholder' => '描述故障或问题...',

    // Warranty Claim
    'warrantyClaim'    => '保修索赔',
    'isWarrantyClaim'  => '这是保修索赔',
    'originalJobId'    => '原工单号',
    'viewOriginalJob'  => '查看原工单',

    // Job Statuses
    'statusNewCheckin'    => '新接收',
    'statusPendingRepair' => '待维修',
    'statusInProgress'    => '维修中',
    'statusRepairDone'    => '维修完成',
    'statusReadyHandover' => '待交付',
    'statusDelivered'     => '已交付',
    'statusCancelled'     => '已取消',

    // ========================================================================
    // Inventory
    // ========================================================================
    'part'              => '零件',
    'partCode'          => '零件编码',
    'partName'          => '零件名称',
    'partDescription'   => '描述',
    'partCostPrice'     => '成本价',
    'partSellPrice'     => '销售价',
    'partQuantity'      => '数量',
    'partLocation'      => '存放位置',
    'partSerialNumber'  => '序列号',
    'reorderPoint'      => '补货点',
    'newPart'           => '新建零件',
    'editPart'          => '编辑零件',
    'partDetails'       => '零件详情',
    'stockLevel'        => '库存水平',
    'lowStock'          => '库存不足',
    'outOfStock'        => '缺货',
    'inStock'           => '有货',
    'searchPart'        => '按编码或名称搜索...',
    'noPartsFound'      => '未找到零件',
    'partCreated'       => '零件创建成功',
    'partUpdated'       => '零件更新成功',
    'partDeleted'       => '零件删除成功',
    'stockTransaction'  => '库存变动记录',
    'lowStockAlert'     => '库存不足提醒',

    // ========================================================================
    // Payment
    // ========================================================================
    'payment'           => '付款',
    'paymentMethod'     => '付款方式',
    'paymentCash'       => '现金',
    'paymentTransfer'   => '转账 / 扫码',
    'paymentReference'  => '参考号',
    'paymentDate'       => '付款日期',
    'paymentAmount'     => '金额',
    'laborCost'         => '人工费',
    'partsCost'         => '零件费',
    'subtotal'          => '小计',
    'vatAmount'         => '增值税 (7%)',
    'grandTotal'        => '总计',
    'amountPaid'        => '已付金额',
    'amountDue'         => '应付余额',
    'paymentReceived'   => '付款成功',
    'insufficientPayment' => '付款金额不足',

    // ========================================================================
    // Settings
    // ========================================================================
    'branch'            => '分店',
    'branchName'        => '分店名称',
    'branchAddress'     => '分店地址',
    'newBranch'         => '新建分店',
    'editBranch'        => '编辑分店',
    'branchCreated'     => '分店创建成功',
    'branchUpdated'     => '分店更新成功',
    'branchDeleted'     => '分店删除成功',

    'user'              => '用户',
    'userRole'          => '角色',
    'roleAdmin'         => '管理员',
    'roleTechnician'    => '技术员',
    'newUser'           => '新建用户',
    'editUser'          => '编辑用户',
    'userCreated'       => '用户创建成功',
    'userUpdated'       => '用户更新成功',
    'userDeleted'       => '用户删除成功',
    'confirmPassword'   => '确认密码',
    'passwordMismatch'  => '密码不匹配',
    'lastLogin'         => '最后登录',

    'vatType'           => '税务类型',
    'vatInclusive'      => '含税 (7%)',
    'vatNone'           => '不含税',
    'settingsSaved'     => '设置保存成功',

    // ========================================================================
    // Dashboard
    // ========================================================================
    'todayJobs'         => '今日工单',
    'monthlyJobs'       => '本月工单',
    'pendingJobs'       => '待处理',
    'completedJobs'     => '已完成',
    'todayRevenue'      => '今日收入',
    'monthlyRevenue'    => '本月收入',
    'lowStockItems'     => '库存不足项目',
    'recentJobs'        => '最近工单',
    'revenueChart'      => '收入 (近7天)',
    'jobsByStatus'      => '工单状态分布',

    // ========================================================================
    // Validation & Messages
    // ========================================================================
    'required'          => '此字段为必填项',
    'invalidEmail'      => '请输入有效的邮箱地址',
    'invalidPhone'      => '请输入有效的电话号码',
    'minLength'         => '最少需要 {0} 个字符',
    'maxLength'         => '最多允许 {0} 个字符',
    'confirmDelete'     => '您确定要删除此项吗？',
    'confirmCancel'     => '您确定要取消此工单吗？',
    'operationSuccess'  => '操作成功',
    'operationFailed'   => '操作失败，请重试',
    'recordNotFound'    => '未找到记录',
    'noRecords'         => '没有记录',
    'loadingData'       => '加载中...',
    'processingRequest' => '处理中...',

    // ========================================================================
    // Quotation
    // ========================================================================
    'quotation'          => '报价单',
    'quotations'         => '报价单',
    'quotationNo'        => '报价单号',
    'newQuotation'       => '新建报价单',
    'editQuotation'      => '编辑报价单',
    'quotationDetails'   => '报价单详情',
    'validUntil'         => '有效期至',
    'proposedSolution'   => '建议方案',
    'issueDescription'   => '问题描述',
    'quotationCreated'   => '报价单创建成功',
    'quotationUpdated'   => '报价单更新成功',
    'quotationApproved'  => '报价单已批准',
    'quotationRejected'  => '报价单已拒绝',
    'convertToJob'       => '转换为工单',
    'quotationStatus'    => '报价单状态',
    'statusDraft'        => '草稿',
    'statusSent'         => '已发送',
    'statusApproved'     => '已批准',
    'statusRejected'     => '已拒绝',
    'statusConverted'    => '已转换',
    'statusExpired'      => '已过期',
    'searchQuotation'    => '搜索报价单或客户...',
    'noQuotationsFound'  => '未找到报价单',
    'allStatus'          => '所有状态',
    'approveQuotation'   => '批准报价单',
    'rejectQuotation'    => '拒绝报价单',
    'quotationTerms'     => '条款与条件',
    'quotationValidDays' => '本报价单自开具之日起30天内有效。',
    'quotationPaymentTerms' => '付款方式：预付50%，完工后支付50%。',
    'quotationWarranty'  => '保修期：零件30天，人工7天。',
    'priceSubjectChange' => '价格可能根据实际维修需求而变化。',

    // ========================================================================
    // Customer Portal
    // ========================================================================
    'trackRepair'        => '跟踪维修',
    'trackYourStatus'    => '跟踪您的维修状态',
    'enterJobId'         => '输入您的工单号',
    'trackNow'           => '立即跟踪',
    'jobNotFound'        => '未找到工单',
    'checkJobIdTryAgain' => '请检查您的工单号后重试。',
    'deviceInfo'         => '设备信息',
    'checkInDate'        => '登记日期',
    'estimatedCost'      => '预估费用',
    'problemDescription' => '问题描述',
    'progressTimeline'   => '进度时间线',
    'deviceReady'        => '您的设备已准备好取回！',
    'bringIdToCollect'   => '取回时请携带身份证和工单号。',
    'repairHistory'      => '维修历史',
    'enterPhone'         => '输入您的电话号码',
    'viewHistory'        => '查看历史',
    'noRepairHistory'    => '未找到维修历史',
    'staffLogin'         => '员工登录',
    'needHelp'           => '需要帮助？',
    'contactUs'          => '联系我们',
    'model'              => '型号',
    'serialNumber'       => '序列号',
    'checkedIn'          => '已登记',
    'awaitingRepair'     => '等待维修',
    'repairInProgress'   => '维修中',
    'repairComplete'     => '维修完成',
    'readyForPickup'     => '准备取回',
    'delivered'          => '已交付',
    'current'            => '当前',
    'professionalService' => '专业矿机硬件维修服务',

    // ========================================================================
    // Backup & Restore
    // ========================================================================
    'backup'             => '备份',
    'restore'            => '恢复',
    'backupRestore'      => '备份与恢复',
    'createBackup'       => '创建备份',
    'createBackupNow'    => '立即创建备份',
    'restoreBackup'      => '恢复备份',
    'availableBackups'   => '可用备份',
    'uploadBackupFile'   => '上传备份文件',
    'noBackupsFound'     => '未找到备份',
    'backupCreated'      => '备份创建成功',
    'backupRestored'     => '备份恢复成功',
    'backupDeleted'      => '备份删除成功',
    'confirmRestore'     => '您确定要恢复此备份吗？',
    'restoreWarning'     => '警告：这将覆盖所有当前数据！',
    'backupTips'         => '备份提示',
    'filename'           => '文件名',
    'fileSize'           => '大小',
    'created'            => '创建时间',
    'download'           => '下载',
    'backupDescription'  => '创建数据库完整备份，包括所有客户、工单、库存和设置。',
    'restoreDescription' => '从备份文件恢复数据库。',
    'backupTip1'         => '定期创建备份，特别是在进行重大更改之前。',
    'backupTip2'         => '下载并将备份存储在安全的位置（云存储、外部硬盘）。',
    'backupTip3'         => '压缩（.gz）备份使用较少的存储空间。',
    'backupTip4'         => '定期通过恢复到测试环境来测试您的备份。',
    'backupTip5'         => '始终保留至少3个最新备份。',
    'backToSettings'     => '返回设置',

    // ========================================================================
    // Credit System
    // ========================================================================
    'creditSettings'     => '信用设置',
    'creditLimit'        => '信用额度',
    'creditUsed'         => '已用信用',
    'creditAvailable'    => '可用信用',
    'creditTerms'        => '信用条款',
    'creditBalance'      => '信用余额',
    'outstandingBalance' => '未付余额',

    // ========================================================================
    // UI/Theme
    // ========================================================================
    'darkMode'           => '深色模式',
    'lightMode'          => '浅色模式',
    'theme'              => '主题',
    'switchTheme'        => '切换主题',
    'language'           => '语言',
    'selectLanguage'     => '选择语言',
    'english'            => '英语',
    'thai'               => '泰语',
    'chinese'            => '中文',

    // ========================================================================
    // Settings Extended
    // ========================================================================
    'companyInfo'        => '公司信息',
    'companyName'        => '公司名称',
    'companyLogo'        => '公司标志',
    'taxId'              => '税号',
    'taxSettings'        => '税务设置',
    'vatRate'            => '增值税率',
    'systemConfig'       => '系统配置',
    'warrantyDays'       => '保修期',
    'days'               => '天',
    'jobIdPrefix'        => '工单编号前缀',
    'jobIdPrefixHint'    => '例如，JOB- 将显示为 JOB-001',
    'currency'           => '货币',
    'defaultLanguage'    => '默认语言',
    'uploadLogo'         => '上传标志',
    'uploadNewLogo'      => '上传新标志',
    'deleteLogo'         => '删除标志',
    'logoUploaded'       => '标志上传成功',
    'logoDeleted'        => '标志删除成功',
    'noLogoUploaded'     => '尚未上传标志',
    'logoHint'           => '支持：JPG、PNG、GIF、WebP（最大2MB）',
    'uploadFailed'       => '上传失败',
    'invalidFileType'    => '文件类型无效',
    'fileTooLarge'       => '文件太大',
    'quickLinks'         => '快速链接',
    'branchManagement'   => '分支管理',
    'userManagement'     => '用户管理',
    'addBranch'          => '添加分支',
    'usernameCannotChange' => '用户名无法更改',
    'leaveBlankPassword' => '留空以保持当前密码',
    'userStatusNote'     => '禁用的用户无法登录',
];

