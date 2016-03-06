class SharesController < ApplicationController
  before_action :set_share, only: [:show, :edit, :update, :destroy, :report]

  # GET /shares
  # GET /shares.json
  # GET /users/1/shares.json
  def index
    if params[:creation_date].present?
      @shares = Share.from_date Date.parse params[:creation_date]
    else
      @shares = Share.active
    end

    @shares = @shares.where(user_id: params[:user_id].to_i) if params[:user_id].present?
    @shares = @shares.where(school_id: params[:school_id].to_i) if params[:school_id].present?

    respond_to do |format|
      format.json do
        render :json => @shares.as_json #(:include => { :supporters => Interaction.all.as_json })
      end
    end
  end

  def list_popular
    render :json => Share.popular.first(5).as_json
  end

  # GET /shares/1
  # GET /shares/1.json
  def show
  end

  # GET /shares/new
  def new
    @share = Share.new
  end

  # GET /shares/1/edit
  def edit
  end

  # POST /shares
  # POST /shares.json
  def create
    @share = Share.new(share_params)
    @share.user_id = params[:user_id].to_i if params[:user_id].present?

    respond_to do |format|
      if @share.save
        format.html { redirect_to @share, notice: 'Share was successfully created.' }
        format.json { render :show, status: :created, location: @share }
      else
        format.html { render :new }
        format.json { render json: @share.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /shares/1
  # PATCH/PUT /shares/1.json
  def update
    respond_to do |format|
      if @share.update(share_params)
        format.html { redirect_to @share, notice: 'Share was successfully updated.' }
        format.json { render :show, status: :ok, location: @share }
      else
        format.html { render :edit }
        format.json { render json: @share.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /shares/1
  # DELETE /shares/1.json
  def destroy
    @share.destroy
    respond_to do |format|
      format.html { redirect_to shares_url, notice: 'Share was successfully destroyed.' }
      format.json { head :no_content }
    end
  end

  # POST /shares/1/report.json
  def report
    @share.active = false

    respond_to do |format|
      if @share.save
        format.html { redirect_to @share, notice: 'Share was successfully reported.' }
        format.json { render :show, status: :created, location: @share }
      else
        format.html { render :new }
        format.json { render json: @share.errors, status: :unprocessable_entity }
      end
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_share
      @share = Share.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def share_params
      params.require(:share).permit(:title, :description, :category, :school_id, :user_id, :date, :icon)
    end
end
