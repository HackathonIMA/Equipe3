class Share < ActiveRecord::Base
  belongs_to :user
  belongs_to :school
  has_many :interactions

  scope :active, -> {where active: true}

  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }

  def Share.from_date(date)
    Share.active.where("created_at >= ?", date.to_time.beginning_of_day)
  end

  def as_json(options = {})
    options.merge!(:include => { interactions })
    super({  }.merge(options || {}))
  end
end
