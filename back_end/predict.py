# 脚本运行依赖paddlex
# pip install paddlex
import warnings

with warnings.catch_warnings():
    warnings.filterwarnings("ignore",category=DeprecationWarning)
    import paddlex as pdx
    import cv2


# 模型加载, 请将path_to_model替换为你的模型导出路径
# 可使用 mode = pdx.load_model('path_to_model') 加载
# 而使用Predictor方式加载模型，会对模型计算图进行优化，预测速度会更快
print("Loading model...")
model = pdx.deploy.Predictor('D:\\traing\P0003-T0008_export_model\inference_model', use_gpu=True)
print("Model loaded.")

# 模型预测, 可以将图片替换为你需要替换的图片地址
# 使用Predictor时，刚开始速度会比较慢，参考此issue
# https://github.com/PaddlePaddle/PaddleX/issues/116
im = cv2.imread('D:\\traing\\P0003-T0008_export_model\\photo\\19.jpg')
#图片位置
im = im.astype('float32')

result = model.predict(im)

# 输出分类结果
if model.model_type == "classifier":
    print(result)

# 可视化结果, 对于检测、实例分割务进行可视化#


# 可视化结果, 对于语义分割务进行可视化
if model.model_type == "segmenter":
    # weight用于调整结果叠加时原图的权重
    # 可视化结果保存在当前目录
    pdx.seg.visualize(im, result, weight=0.0, save_dir='./')
